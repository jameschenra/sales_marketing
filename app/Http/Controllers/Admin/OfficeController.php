<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\OfficeOpening;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Office');
        $param['page_description'] = trans('main.List');
        $param['officies'] = Office::paginate(PAGINATION_SIZE);

        return view('admin.pages.office.index', $param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Office');
        $param['page_description'] = trans('main.Create');
        $param['users'] = User::all();

        return view('admin.pages.office.form', $param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Office');
        $param['page_description'] = trans('main.Edit');
        $param['users'] = User::all();
        $param['office'] = Office::find($id);

        return view('admin.pages.office.form', $param);
    }

    public function store(Request $request)
    {
        $rules = ['name' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if ($request->has('office_id')) {
                $id = $request->input('office_id');
                $office = Office::find($id);
                $officeOpening = Office::find($id)->opening;
            } else {
                $office = new Office;
                $officeOpening = new OfficeOpening;
            }
            $office->user_id = $request->input('user_id');
            $office->name = $request->input('name');
            $office->address = $request->input('address');
            $office->city_id = $office->city_id ?: null;
            $office->country_id = $office->country_id ?: null;
            $office->zip_code = $office->zip_code ?: null;
            $office->lat = $request->input('lat');
            $office->lng = $request->input('lng');
            $office->phone_number = $request->input('phone_number');
            $office->holidays = $request->input('holidays');
            $office->save();

            $officeOpening->office_id = $office->id;
            foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $key) {
                $officeOpening->{$key . '_start'} = $request->input($key . '_start');
                $officeOpening->{$key . '_end'} = $request->input($key . '_end');
            }
            $officeOpening->save();
        }

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        return redirect()->route('admin.office.index');
    }

    public function delete($id)
    {
        try {
            Office::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
