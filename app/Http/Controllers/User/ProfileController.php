<?php

namespace App\Http\Controllers\User;

use Image;
use App\User;
use Validator;
use App\Models\Book;
use App\Models\Post;
use App\Models\Office;
use App\Enums\UserType;
use App\Models\Country;
use App\Models\Service;
use App\Models\EnrollType;
use App\Models\UserDetail;
use App\Models\Association;
use App\Models\CompanyType;
use Illuminate\Http\Request;
use App\Models\ServiceOffice;
use App\Models\ServiceCategory;
use App\Models\UserBillingInfo;
use App\Models\ProfessionByUser;
use App\Mail\PasswordChangedMail;
use App\Models\ProfessionCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Repositories\BillingRepository;
use App\Models\Language as LanguageModel;
use App\Http\Requests\User\BillingRequest;
use App\Http\Requests\User\ProfileRequest;

class ProfileController extends Controller
{
    public function profileWizard($step = 'profile')
    {
        $user = auth()->user();
        $stepStatus = $this->getStepStatus($step);
        $data = $this->getDataByStep($step);

        $data['is_first_login'] = $user->login_count == 1 ? true : false;
        $data['step'] = $step;
        $data['stepStatus'] = $stepStatus;
        $data['mode'] = 'profile-wizard';

        if ($user->login_count == 1) {
            $user->login_count = 2;
            $user->save();
        }

        return view('user.pages.profile.profile-wizard', $data);
    }

    public function edit()
    {
        $data = $this->getDataByStep('profile');
        $data['is_first_login'] = false;
        $data['mode'] = 'edit-profile';

        return view('user.pages.profile.profile', $data);
    }

    public function store(ProfileRequest $request)
    {
        $user = auth()->user();

        DB::beginTransaction();

        try {
            $user->update($request->all());

            $mode = $request->input('mode');
            if ($mode == 'profile-wizard') {
                $user->type = UserType::SELLER;
                $user->save();
            }

            $userDetail = $user->detail;
            if ($user->type == UserType::SELLER) {
                $languages = implode(',', $request->input('language'));
                $userDetail->enroll_type = $request->input('enroll_type');
                $userDetail->association_id = $request->input('association_id');
                $userDetail->languages = $languages;
                $userDetail->city = $request->input('city');
                if ($userDetail->enroll_type == EnrollType::NOT_ENROLLED) {
                    $userDetail->reg_number = null;
                } else {
                    $userDetail->reg_number = $request->input('reg_number');
                }
                $userDetail->description_en = $request->input('description_en');
                $userDetail->description_it = $request->input('description_it');
                $userDetail->description_es = $request->input('description_es');
            }
            $userDetail->country_id = $request->input('country_id');
            $userDetail->photo = $request->input('photo');

            if ($user->type == UserType::SELLER) {
                ProfessionByUser::where('user_id', $user->id)->delete();
                $profs = json_decode($request->input('professions'));
                if (!empty($profs)) {
                    foreach ($profs as $prof) {
                        ProfessionByUser::create([
                            'user_id' => $user->id,
                            'profession_category_id' => $prof->category_id,
                            'profession_id' => $prof->profession_id,
                        ]);
                    }
                }
            }

            if ($mode == 'profile-wizard' && $userDetail->profile_wizard_completed == UserDetail::NOTHING_COMPLETED) {
                $userDetail->profile_wizard_completed = UserDetail::PROFILE_COMPLETED;
            }
            
            $userDetail->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return $this->redirectErrorBack();
        }

        if ($request->input('profile_save') == 'profile-wizard') {
            return redirect()->route('user.profile.wizard', 'contact');
        } else {
            session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Profile saved successfully')]);
            return redirect()->back();
        }
    }

    public function uploadPhoto(Request $request)
    {
        // upload photo
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $img = Image::make($photoFile);
            $img->orientate();

            if ($img->width() > 600) {
                $img->resize(600, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $photoFileName = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
            $filePath = ABS_USER_PATH . $photoFileName;
            $img->save($filePath, 80, 'jpg');

            return response()->json($photoFileName);
        }
    }

    public function billingDetail()
    {
        $param['billing_info'] = auth()->user()->billingInfo;
        $param['company_types'] = CompanyType::get();
        $param['countries'] = Country::get();
        return view('user.pages.profile.billing', $param);
    }

    public function storeBilling(BillingRequest $request)
    {
        BillingRepository::storeBillingInfo($request);

        $alert = [
            'msg' => trans('main.Invoice Update Successfully'),
            'type' => 'success',
        ];
        session()->flash('alert', $alert);

        return redirect()->back();
    }

    private function getDataByStep($step)
    {
        $data = [];

        switch ($step) {
            case 'profile':
                $data['countries'] = Country::getOrderByShortName();
                $data['languages'] = LanguageModel::getOrderByName();
                $data['professions'] = ProfessionCategory::getWithProfessions();
                $data['enroll_types'] = EnrollType::get();
                $data['associations'] = Association::orderBy('name_' . app()->getLocale())->get();
                break;
            case 'contact':
                $data['countries'] = Country::getOrderByShortName();
                $data['office'] = Office::where('user_id', auth()->id())->first();
                $data['company_types'] = CompanyType::get();
                $data['billing_info'] = auth()->user()->billingInfo;
                break;
            default:
                break;
        }

        return $data;
    }

    private function getStepStatus($step)
    {
        $stepNumber = 1;
        $stepStatus = [
            'profile' => 'current',
            'contact' => false,
        ];

        switch ($step) {
            case 'contact':
                $stepNumber = 2;
                break;
            default:
                $step = 'profile';
        }

        $i = 0;
        foreach ($stepStatus as $idx => &$status) {
            $i++;
            if ($i < $stepNumber) {
                $status = 'done';
            } else if ($i == $stepNumber) {
                $status = 'current';
            } else {
                $status = false;
            }
        }

        return $stepStatus;
    }

    public function showSetting()
    {
        $param['user'] = auth()->user();
        return view('user.pages.profile.settings', $param);
    }

    public function updateSetting(Request $request)
    {
        $user = auth()->user();
        if ($request->has('min_balance_notification')) {
            $user->detail->unsubscribe_minimum_credit = $request->min_balance_notification;
            session(['showNotificationLowBalance' => 1]);
        } else {
            $user->detail->unsubscribe_minimum_credit = 0;
        }

        if ($user->detail->save()) {
            $msg = $user->detail->unsubscribe_minimum_credit == 1 ?
            trans('main.Settings.pay.in.the.office.free.service.on') :
            trans('main.Settings.pay.in.the.office.free.service.off');
            $type = 'success';
        } else {
            $msg = "Error while update notification setting";
            $type = 'error';
        }

        session()->flash('alert', [
            'msg' => $msg,
            'type' => $type,
        ]);

        return redirect()->route('user.settings');
    }

    public function detailProfile($slug, Request $request)
    {
        $user = User::findBySlug($slug);
        if (auth()->id() != $user->id && $user->has_service != 1) {
            abort(404);
        }

        $param['user'] = $user;
        $param['prof_id'] = $user->id;
        $param['prof_name'] = $user->name;

        // $param['services'] = Service::getAvailableServiceByUserId($user->id);
        $param['services'] = Service::with(['user', 'user.balance'])
            ->where('user_id', $user->id)
            ->where('active', 1)
            ->paginate(5);
        $param['posts'] = Post::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(4);
        $param['isOffsite'] = ServiceOffice::isOffsiteByUserId($user->id);
        $books = Book::with('review', 'service')
            ->where('seller_id', $user->id)
            ->whereNotNull('review_id')
            ->get(['id', 'user_id', 'service_id', 'review_id']);
        $param['sort_by'] = $request->sort_by;
        $param['sort_array'] = [
            'newest_first' => trans('main.Newest First'),
            'highest_rated' => trans('main.Highest Rated'),
            'lowest_rated' => trans('main.Lowest Rated'),
            'by_services' => trans('main.By Services'),
            'by_services_name' => trans('main.By Service Name'),
        ];

        if (!isset($request->sort_by) || $request->sort_by == 'newest_first') {
            $param['books'] = $books->sortByDesc(function ($product, $key) {
                return $product->review->created_at;
            });
        } elseif ($request->sort_by == 'highest_rated') {
            $param['books'] = $books->sortByDesc(function ($product, $key) {
                return $product->review->rate;
            });
        } elseif ($request->sort_by == 'lowest_rated') {
            $param['books'] = $books->sortBy(function ($product, $key) {
                return $product->review->rate;
            });
        } elseif ($request->sort_by == 'by_services') {
            $param['books'] = $books->sortBy(function ($product, $key) {
                return $product->service_id;
            });
        } elseif ($request->sort_by == 'by_services_name') {
            $param['books'] = $books->sortBy(function ($product, $key) {
                return $product->service->name;
            });
        }

        return view('user.pages.profile.detail-profile')->with($param);
    }

    public function showChangePassword()
    {
        $param['user'] = auth()->user();

        return view('user.pages.profile.change-password', $param);
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'password_current' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        $messages = [
            'password_current.required' => trans('main.Enter the current password'),
            'password.required' => trans('main.Enter a new password'),
            'password.confirmed' => trans('main.The password confirmation does not match'),
            'password_confirmation.required' => trans('main.Retype new password'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        if (password_verify($request->input('password_current'), $user->password)) {
            $user->password = bcrypt($request->input('password'));
            $user->save();

            session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Password has been updated successfully')]);
            Mail::to($user)->queue(new PasswordChangedMail(App::getLocale(), $user));

            return redirect()->route('user.profile.password.show');
        } else {
            return redirect()->back()->withErrors(['password_current' => trans('main.Current Password is incorrect')]);
        }
    }
}
