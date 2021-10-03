<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Models\Office;
use App\Models\Region;
use App\Models\Country;
use App\Enums\Constants;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Models\OfficeOpening;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\BillingRepository;
use App\Http\Requests\User\OfficeRequest;
use App\Http\Requests\User\BillingRequest;

class OfficeController extends Controller
{
    public function list()
    {
        $params['offices'] = Office::where('user_id', auth()->id())->orderBy('name')->get();

        return view('user.pages.office.list', $params);
    }

    public function create()
    {
        $officeCount = Office::where('user_id', auth()->id())->count();
        if ($officeCount >= 5) {
            $params['over_limit'] = true;
        } else {
            $params['office'] = null;
            $params['countries'] = Country::getOrderByShortName();
            $params['mode'] = 'create';
        }

        return view('user.pages.office.office', $params);
    }

    public function store(Request $request)
    {
        $mode = $request->input('mode');
        if ($mode == 'profile-wizard') {
            $allRules = (new OfficeRequest)->rules() + (new BillingRequest)->rules();
        } else {
            $allRules = (new OfficeRequest)->rules();
        }
        
        Validator::make($request->all(), $allRules)->validate();

        $user = auth()->user();

        foreach (Constants::WEEK_DAYS as $key) {
            if ($this->isInvalidOpeningAndClosingTime($request->input($key . '_start'), $request->input($key . '_end'))) {
                return back()->withErrors(['invalidOpeningClosingTime' => trans('main.invalidClosingTime')])->withInput();
            }
        }

        $officeId = $request->input('office_id');
        if ($officeId) {
            $office = Office::find($officeId);

            $officeOpening = OfficeOpening::where('office_id', $officeId)->first() ?? new OfficeOpening;
        } else {
            $office = new Office;

            $officeOpening = new OfficeOpening;
        }

        DB::beginTransaction();

        try {
            $office->user_id = $user->id;
            $office->name = $request->input('name');
            $office->address = $request->input('address');
            $office->phone_number = $request->input('phone_number');
            $office->city_id = $request->input('city_id');
            $office->region_id = $request->input('region_id');
            $office->country_id = $request->input('country_id');
            $office->zip_code = $request->input('zip_code');
            $office->lat = $request->input('lat');
            $office->lng = $request->input('lng');
            $office->holidays = $request->input('holidays');
            $office->has_calendar = $request->input('has_calendar');
            $office->save();

            $officeOpening->office_id = $office->id;
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $key) {
                $start = $request->input($key . '_start');
                $officeOpening->{$key . '_start'} = $start;
                $officeOpening->{$key . '_end'} = ($start == 'closed') ? 'closed' : $request->input($key . '_end');
            }

            $officeOpening->save();

            if ($request->input('save_next')) {
                $userDetail = $user->detail;
                $userDetail->profile_wizard_completed = UserDetail::CONTACT_COMPLETED;
                $userDetail->save();
            }

            if ($mode == 'profile-wizard') {
                BillingRepository::storeBillingInfo($request);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return $this->redirectErrorBack();
        }

        $alert = [
            'msg' => trans('main.Office has been saved successfully'),
            'type' => 'success',
        ];

        if ($request->input('save_next')) {
            $alert['msg'] = trans('main.office-save-continue-billing');
            session()->flash('from_wizard', true);
            return redirect()->route('user.service.create')->with('alert', $alert);
        } else {
            session()->flash('alert', $alert);
            return redirect()->route('user.office.mylist');
        }
    }

    public function edit($id)
    {
        $params['office'] = Office::findOrFail($id);
        $params['countries'] = Country::getOrderByShortName();
        $params['mode'] = 'edit';

        return view('user.pages.office.office', $params);
    }

    /**
     * Check if start and end time is valid
     */
    private function isInvalidOpeningAndClosingTime($startTime, $endTime)
    {
        if (strtolower($startTime) == 'closed') {
            return false;
        }

        return $startTime > $endTime;
    }
}
