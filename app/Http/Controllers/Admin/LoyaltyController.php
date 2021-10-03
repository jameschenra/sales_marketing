<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class LoyaltyController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Offer');
        $param['page_description'] = trans('main.List');
        $param['loyalties'] = Loyalty::paginate(PAGINATION_SIZE);

        return view('admin.pages.loyalty.index', $param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Loyalty');
        $param['page_description'] = trans('main.Create');
        $param['users'] = User::all();

        return view('admin.pages.loyalty.form', $param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Loyalty');
        $param['page_description'] = trans('main.Edit');
        $param['users'] = User::all();
        $param['loyalty'] = Loyalty::find($id);

        return view('admin.pages.loyalty.form', $param);
    }

    public function store(Request $request)
    {
        $rules = [
            'name_en' => 'required',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->has('loyalty_id')) {
            $id = $request->input('loyalty_id');
            $loyalty = Loyalty::find($id);
        } else {
            $loyalty = new Loyalty;
            $loyalty->photo = DEFAULT_PHOTO;
        }

        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
            $nameCtrl = 'name_' . $localeCode;
            $descCtrl = 'description_' . $localeCode;
            $loyalty->$nameCtrl = $request->input($nameCtrl);
            $loyalty->$descCtrl = $request->input($descCtrl);
        }

        $loyalty->user_id = $request->input('user_id');
        $loyalty->count_stamp = $request->input('count_stamp');
        $loyalty->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        return redirect()->route('admin.loyalty.index');
    }

    public function delete($id)
    {
        try {
            Loyalty::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
