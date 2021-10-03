<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Models\Book;
use App\Models\Offer;
use App\Models\Office;
use App\Models\Review;
use App\Models\Loyalty;
use App\Models\Service;
use App\Models\Feedback;
use App\Models\Favourite;
use App\Models\UserDetail;
use App\Models\UserBalance;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use App\Models\ServiceOffice;
use App\Models\ProfessionByUser;
use App\Models\TransactionOfCredit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $params['page_title'] = trans('main.User');
        $params['page_description'] = trans('main.Users');

        $params['users'] = User::paginate(PAGINATION_SIZE);

        return view('admin.pages.user.index', $params);
    }

    public function create() {
        return view('admin.pages.user.form');
    }

    public function edit($id) {
        $user = User::find($id);

        return view('admin.pages.user.form', compact('user'));
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
            $isNewUser = $request->has('user_id') ? false : true;
            if ($isNewUser) {
                $user = new User;
                $user->is_active = 1;
                $user->type = 1;    // buyer
            } else {
                $user = User::find($request->input('user_id'));
                $userDetail = $user->detail;
            }

            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
                if ($localeCode == 'en') {
                    $nameCtrl = 'name';
                } else {
                    $nameCtrl = 'name' . $localeCode;
                }
                
                $user->$nameCtrl = $request->input($nameCtrl);
            }

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
            }

            if ($request->hasFile('photo')) {
                $filename = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->move(ABS_USER_PATH, $filename);
                $userDetail->photo = $filename;
                $userDetail->save();
            }

            DB::commit();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.User has been saved successfully')]);

            return redirect()->route('admin.user.index');
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
            return redirect()->back()->withError(['msg' => 'main.This user has been already used']);
        }

        return redirect()->back();
    }
}
