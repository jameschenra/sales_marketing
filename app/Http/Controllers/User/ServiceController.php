<?php

namespace App\Http\Controllers\User;

use App\Enums\ClientPaymentType;
use App\Enums\ExtraCostType;
use App\Enums\OnsiteType;
use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ServiceRequest;
use App\Models\ReservedOrder;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceOffice;
use App\Models\ServiceSubCategory;
use App\Repositories\ServiceRepository;
use Browser;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Image;

class ServiceController extends Controller
{
    public function search(Request $request, $category_name = null, $sub_category_name = null)
    {
        $searchParams = $request->all();

        if ($category_name) {
            $category = ServiceCategory::where('slug', $category_name)->first();
            $searchParams += ['category_name' => $category_name];

            if ($sub_category_name) {
                $searchParams += ['sub_category_name' => $sub_category_name];
            }

            $param['sub_categories'] = ServiceSubCategory::where('category_id', $category->id)
                ->orderBy('name_' . app()->getLocale(), 'ASC')
                ->get();
        }

        $serviceRepo = new ServiceRepository();
        $param['services'] = $serviceRepo->filter($searchParams)
            ->with('user', 'user.balance', 'subCategory', 'offices.office')
            ->paginate(PAGINATION_SIZE);

        $param['selected_category_slug'] = $category_name ?: '';
        $param['selected_sub_category_slug'] = $sub_category_name ?: '';
        $param['categories'] = ServiceCategory::getOrderByName();
        $param['pageNo'] = 600;
        $param['is_mobile'] = Browser::isMobile();

        return view('user.pages.service.search', $param);
    }

    public function myList(Request $request)
    {
        $query = Service::where('user_id', auth()->id());

        if ($request->filter == 'published') {
            $query->where('active', 1);
        }

        if ($request->filter == 'drafts') {
            $query->where('active', 0);
        }

        $params['services'] = $query->orderByDesc('created_at')->get();

        $allServices = Service::where('user_id', auth()->id())->get();
        $params['num_services'] = count($allServices);
        $params['published'] = $params['drafts'] = 0;
        foreach ($allServices as $service) {
            if ($service->active) {
                $params['published']++;
            } else {
                $params['drafts']++;
            }
        }

        return view('user.pages.service.list', $params);
    }

    public function create()
    {
        $params['service'] = new Service();
        $params['categories'] = ServiceCategory::getWithSubCategory();
        $params['offices'] = auth()->user()->offices()->get();
        $params['officeCount'] = count($params['offices']);
        $params['payment_types'] = ClientPaymentType::TYPES;
        $params['extra_price_types'] = ExtraCostType::TYPES;
        $params['on_site_types'] = OnsiteType::TYPES;
        $params['mode'] = 'Create';

        return view('user.pages.service.service-form', $params);
    }

    public function store(ServiceRequest $request)
    {
        $serviceId = $request->input('service_id');
        $catId = $request->input('category_id');
        $selectedCategory = ServiceCategory::find($catId);

        DB::beginTransaction();
        try {
            if ($serviceId) {
                $postType = 'update';
                $service = Service::find($serviceId);
                $service->slug = null;
            } else {
                $postType = 'create';
                $service = new Service();

                $service->token = strtoupper(str_random(5));
                $service->salt = str_random(8);
                $service->secure_key = md5($service->salt . '');
                $service->user_id = auth()->id();
                $service->has_video_call = 0;
            }

            $service->photo = $request->input('photo');
            if (!$service->photo) {
                $filename = Str::random(24) . "." . pathinfo($selectedCategory->image, PATHINFO_EXTENSION);
                File::copy(public_path('img/app_category/' . $selectedCategory->image), ABS_SERVICE_PATH . $filename);
                $service->photo = $filename;
            }

            // filter contacts info in name and description
            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $nameAttr = 'name_' . $localeCode;
                $descAttr = 'description_' . $localeCode;
                $service->$nameAttr = StringHelper::filterContactInfos($request->input($nameAttr));
                $service->$descAttr = StringHelper::filterContactInfos($request->input($descAttr));
            }

            $service->category_id = $request->input('category_id');
            $service->sub_category_id = $request->input('sub_category_id');
            $service->provide_online_type = $request->input('provide_online_type');
            $service->has_video_call = $request->input('has_video_call');

            $isMultipleService = $request->input('is_multiple_service');
            $isAvailableOffsite = $request->input('is_available_offsite');

            if ($request->has('save_draft')) {
                $service->active = 0;
            } else {
                $service->active = 1;
            }

            if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE) {
                $service->online_delivery_time = $request->input('online_delivery_time');
                $service->online_office_id = $request->input('online_office_id');
                $service->online_book_count = $request->input('online_book_count');
                $service->online_revision = $request->input('online_revision');
                $service->online_file_required = $request->input('online_file_required');
                $service->price = $request->input('online_price');
                $service->discount_percentage_single = floor($request->input('online_discount_percentage_single'));
                $service->booking_confirm = Service::BOOKING_DIRECTLY;

                if ($service->online_book_count > 1) {
                    $service->discount_percentage_multiple = floor($request->input('online_discount_percentage_multiple'));
                } else {
                    $service->discount_percentage_multiple = null;
                }

                $service->save();

                ServiceOffice::deleteByServiceId($service->id);
            } else {
                $service->duration = $request->input('duration');
                $service->client_payment_type = $request->input('client_payment_type');
                if ($service->client_payment_type == Service::PAYMENT_TYPE_FREE) {
                    $service->price = 0;
                    $service->discount_percentage_single = null;
                    $service->discount_percentage_multiple = null;
                    $service->extra_price_type = null;
                    $service->extra_price = 0;
                    $service->booking_confirm = Service::BOOKING_CONFIRM;
                } else {
                    $service->price = $request->input('offline_price');
                    $service->discount_percentage_multiple = null;
                    $service->discount_percentage_single = null;

                    if ($service->client_payment_type == Service::PAYMENT_TYPE_ONSITE) {
                        $service->booking_confirm = Service::BOOKING_CONFIRM;
                    } else {
                        $service->booking_confirm = $request->input('confirm_first_service_book');
                        $service->discount_percentage_single = floor($request->input('offline_discount_percentage_single'));
                        if ($isMultipleService == 1) {
                            $service->discount_percentage_multiple = floor($request->input('offline_discount_percentage_multiple'));
                        }
                    }

                    if ($isAvailableOffsite == 1) {
                        $service->extra_price_type = $request->input('extra_price_type');
                        if ($service->extra_price_type != Service::EXTRA_PRICE_NO) {
                            $service->extra_price = $request->input('extra_price');
                        } else {
                            $service->extra_price = 0;
                        }
                    } else {
                        $service->extra_price_type = null;
                        $service->extra_price = 0;
                    }
                }
                $service->save();

                ServiceOffice::deleteByServiceId($service->id);
                $officeInfos = $request->input('office_info');

                if ($officeInfos && is_array($officeInfos)) {
                    $idx = 0;
                    foreach ($officeInfos as $officeInfo) {
                        if (isset($officeInfo['office_id'])) {
                            $data = [
                                'user_id' => auth()->id(),
                                'service_id' => $service->id,
                                'office_id' => $officeInfo['office_id'],
                                'book_count' => $officeInfo['book_count'],
                                'book_consecutively' => $officeInfo['book_consecutively'],
                                'onsite_type' => $officeInfo['onsite_type'],
                            ];
                            if ($officeInfo['onsite_type'] != ServiceOffice::TYPE_ONSITE) {
                                $data = array_merge($data, [
                                    'provide_range' => $officeInfo['provide_range'] ?? null,
                                ]);
                            }
                            ServiceOffice::create($data);
                        }
                    }
                }
            }

            DB::commit();

            $user = auth()->user();
            $lowBalanceCheck = ($user->wallet_balance < MINIMUM_BALANCE) ? true : false;

            $serviceCount = Service::where("user_id", $user->id)->count();

            if ($request->has('save_draft')) {
                $message = trans('main.Great! Your service has saved as a draft');
            } else {
                if ($user->unsubscribe_minimum_credit == 0) {
                    if ($postType == 'create') {
                        $message = trans('main.Great! Your service has been published');
                    } else {
                        $message = trans('main.Great! Your service has been updated');
                    }
                } else {
                    if ($serviceCount == '1') {
                        if ($lowBalanceCheck && $service->price == 0) {
                            $message = trans('main.publish first free service with low balance');
                        } else if ($lowBalanceCheck && $service->price > 0) {
                            $message = trans('main.publish first service with charge land ow balance');
                        } else {
                            $message = trans('main.Congratulations! Your first service has been published');
                        }
                    } else {
                        if ($lowBalanceCheck && $service->price == 0) {
                            $message = trans('main.publish other free service with low balance');
                        } else {
                            if ($postType == 'create') {
                                $message = trans('main.Great! Your service has been published');
                            } else {
                                $message = trans('main.Great! Your service has been updated');
                            }
                        }
                    }
                }
            }

            session()->flash('alert', [
                'type' => 'success',
                'msg' => $message,
            ]);

            return redirect()->route('user.service.mylist');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->redirectErrorBack();
        }
    }

    public function edit($id)
    {
        $params['categories'] = ServiceCategory::getWithSubCategory();
        $params['offices'] = auth()->user()->offices()->get();
        $params['officeCount'] = count($params['offices']);
        $params['service'] = Service::with('offices')->findOrFail($id);
        $params['payment_types'] = ClientPaymentType::TYPES;
        $params['extra_price_types'] = ExtraCostType::TYPES;
        $params['on_site_types'] = OnsiteType::TYPES;
        $params['mode'] = 'Edit';

        return view('user.pages.service.service-form', $params);
    }

    public function showBooking($slug)
    {
        $service = Service::whereSlug($slug)->with('user', 'user.profsByUser', 'user.profsByUser.profession',
            'category', 'subCategory')->firstOrFail();
        $ownerUser = $service->user;

        $param['service'] = $service;
        $param['owner'] = $ownerUser;
        $param['is_online'] = $service->provide_online_type == Service::PROVIDE_ONLINE_TYPE;
        $param['is_offline'] = !$param['is_online'];

        $offices = [];
        if ($service->provide_online_type == Service::PROVIDE_OFFLINE_TYPE) {
            $serviceOffices = ServiceOffice::where('service_id', $service->id)->with('office', 'office.country')->get();
            foreach ($serviceOffices as $serviceOffice) {
                $office = $serviceOffice->office;
                $offices[] = [
                    'office_id' => $office->id,
                    'service_office_id' => $serviceOffice->id,
                    'book_count' => $serviceOffice->book_count,
                    'book_consecutively' => $serviceOffice->book_consecutively,
                    'onsite_type' => $serviceOffice->onsite_type,
                    'provide_range' => $serviceOffice->provide_range,
                    'address' => ($office->city->name ?? '') . ', '
                    . ($office->region->name ?? '') . ', '
                    . ($office->country->short_name ?? ''),
                    'city' => $office->city->name,
                    'lat' => $office->lat,
                    'lng' => $office->lng,
                    'week_holidays' => $office->weeklyHolidays(),
                    'holidays' => $office->holidays,
                    'booked_days' => $office->getFullyBookedDaysFor($service->id),
                    'reserved_days' => ReservedOrder::getByServiceOffice($service->id, $office->id),
                ];
            }
        } else {
            $office = $service->onlineOffice;

            $offices[] = [
                'office_id' => $office->id,
                'service_office_id' => null,
                'book_count' => $service->online_book_count,
                'revision' => $service->online_revision,
                'address' => $office->address,
                'city' => $office->city->name,
                'lat' => $office->lat,
                'lng' => $office->lng,
                'week_holidays' => $office->weeklyHolidays(),
                'holidays' => $office->holidays,
                'booked_days' => [],
                'reserved_days' => ReservedOrder::getByServiceOffice($service->id, $office->id),
            ];
        }

        $param['offices'] = $offices;
        $param['offices_count'] = count($offices);
        $param['is_mobile'] = Browser::isMobile();

        $service->count_view = $service->count_view + 1;
        $service->save();

        return view('user.pages.service.booking', $param);
    }

    public function uploadPhoto(Request $request)
    {
        // upload photo
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $img = Image::make($photoFile);
            $img->orientate();

            if ($img->width() > 1200) {
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $photoFileName = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
            $filePath = ABS_SERVICE_PATH . $photoFileName;
            $img->save($filePath, 80, 'jpg');

            return response()->json($photoFileName);
        }
    }

    public function quoteStore(Request $request)
    {
        return redirect()->route('user.profile.wizard', 'service');
    }

    public function listSubCategoryByCatId($catId = null)
    {
        $subCats = ServiceSubCategory::getByCatId($catId);
        return response()->json($subCats->toArray());
    }
}
