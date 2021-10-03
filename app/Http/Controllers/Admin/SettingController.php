<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function edit()
    {
        $param['page_title'] = trans('main.Website');
        $param['page_description'] = trans('main.Management');
        $param['website_settings'] = WebsiteSetting::all()->sortBy('order_id');

        return view('admin.pages.app-setting.form', $param);
    }

    public function store(Request $request)
    {
        $param['page_title'] = trans('main.Plan');
        $param['page_description'] = trans('main.Create');

        $inputs = $request->all();
        foreach ($inputs as $name => $value) {
            if ($name != '_token') {
                $setting = WebsiteSetting::where('name', $name)->first();
                $setting->value = $value;
                $setting->save();
            }
        }

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        return redirect()->back();
    }
}
