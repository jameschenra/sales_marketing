<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Models\Book;
use App\Models\Offer;
use App\Models\Office;
use App\Models\Review;
use App\Enums\UserType;
use App\Models\Country;
use App\Models\Loyalty;
use App\Models\Service;
use App\Models\Feedback;
use App\Models\Language;
use App\Models\Favourite;
use App\Models\EnrollType;
use App\Models\Profession;
use App\Models\UserDetail;
use App\Models\UserBalance;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Models\ServiceOffice;
use App\Models\UserBillingInfo;
use App\Models\ProfessionByUser;
use App\Models\TransactionOfCredit;
use App\Models\ProfessionCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $params['page_title'] = trans('main.Professional');
        $params['page_description'] = trans('main.Professional') . ' ' . trans('main.List');

        $params['companies'] = User::paginate(PAGINATION_SIZE);

        return view('admin.pages.company.index', $params);
    }

    public function create() {
        $param['page_title'] = trans('main.Professional');
        $param['page_description'] = trans('main.Professional') . ' ' . trans('main.Create');

        $param['categories'] = ProfessionCategory::all();
        $param['enroll_types'] = EnrollType::all();
        $param['countries'] = Country::getOrderByShortName();
        $param['languages'] = Language::getOrderByName();

        return view('admin.pages.company.form', $param);
    }

    public function edit($id) {
        $param['page_title'] = trans('main.Professional');
        $param['page_description'] = trans('main.Professional') . ' ' . trans('main.Edit');

        $param['company'] = User::find($id);
        $param['categories'] = ProfessionCategory::all();
        $param['enroll_types'] = EnrollType::all();
        $param['countries'] = Country::getOrderByShortName();
        $param['languages'] = Language::getOrderByName();

        return view('admin.pages.company.form', $param);
    }

    public function block($id) {
        $param['users'] = User::paginate(PAGINATION_SIZE);
        User::where('id', $id)->update(['is_suspend' => 1]);

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.user blocked')]);
        return redirect()->route('admin.user.index');
    }

    public function unblock($id) {
        $param['users'] = User::paginate(PAGINATION_SIZE);
        User::where('id', $id)->update(['is_suspend' => 0]);

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.user unblocked')]);
        return redirect()->route('admin.user.index');
    }

    public function store(Request $request) {
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $isNewUser = $request->has('company_id') ? false : true;
            if ($isNewUser) {
                $user = new User;
                $user->is_active = 1;
                $user->type = UserType::BUYER;    // buyer
            } else {
                $user = User::find($request->input('company_id'));
                $userDetail = $user->detail;
                $userBilling = $user->billingInfo;
            }

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');

            if ($request->has('password')) {
                $user->password = Hash::make($request->input('password'));
            } else if ($isNewUser) {
                $user->password = Hash::make('123456');
            }
            $user->save();

            if ($isNewUser) {
                UserSetting::create([
                    'user_id' => $user->id,
                    'agree_privacy' => 1,
                    'agree_data' => 1,
                    'agree_update' => 1,
                ]);
        
                $userDetail = UserDetail::create([
                    'user_id' => $user->id
                ]);
        
                UserBalance::create([
                    'user_id' => $user->id
                ]);

                $userBilling = new UserBillingInfo();

            }

            if ($request->hasFile('photo')) {
                $filename = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->move(ABS_USER_PATH, $filename);
                $userDetail->photo = $filename;
            }

            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
                $descField = 'description_' . $localeCode;
                
                $userDetail->$descField = $request->input($descField);
            }

            $userDetail->country_id = $request->input('country');
            $userDetail->save();

            // $userDetail->invoice_vat_id = $request->input('vat_id');
            // $userDetail->invoice_unique_code = $request->input('keyword');

            ProfessionByUser::where('user_id', $user->id)->delete();
            if ($request->has('profession')) {
                foreach ($request->input('profession') as $professionId) {
                    $profession = Profession::find($professionId);
                    $companyProfession = new ProfessionByUser;
                    $companyProfession->user_id = $user->id;
                    $companyProfession->profession_category_id = $profession->category_id;
                    $companyProfession->profession_id = $professionId;
                    $companyProfession->save();
                }
            }

            DB::commit();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.User has been saved successfully')]);

            return redirect()->route('admin.company.index');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }

        return redirect()->back()->withErrors(['msg' => 'Something is wrong']);
    }

    public function delete($id) {
        try {
            TransactionOfCredit::where('user_id', $id)->delete();
            Book::where('user_id', $id)->delete();
            Favourite::where('user_id', $id)->delete();
            Feedback::where('user_id', $id)->delete();
            Loyalty::where('user_id', $id)->delete();
            Offer::where('user_id', $id)->delete();
            Office::where('user_id', $id)->delete();
            ProfessionByUser::where('user_id', $id)->delete();
            Review::where('user_id', $id)->delete();
            ServiceOffice::where('user_id', $id)->delete();
            Service::where('user_id', $id)->delete();
            User::find($id)->delete();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.user has been deleted successfully')]);
        } catch (\Exception $ex) {
            dd($ex);
            return redirect()->back()->withError(['msg' => 'main.This user has been already used']);
        }

        return redirect()->back();
    }
}
