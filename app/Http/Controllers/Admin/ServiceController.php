<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ServiceOffice;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Service');
        $param['page_description'] = trans('main.List');
        $param['services'] = Service::paginate(PAGINATION_SIZE);

        return view('admin.pages.service.index')->with($param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Service');
        $param['page_description'] = trans('main.Create');
        $param['categories'] = ServiceCategory::all();
        $param['users'] = User::all();

        return view('admin.pages.service.form')->with($param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Service');
        $param['page_description'] = trans('main.Edit');
        $param['profCategory'] = ServiceCategory::find($id);
        $param['users'] = User::all();
        $param['service'] = Service::find($id);

        return view('admin.pages.service.form')->with($param);
    }

    public function store(Request $request)
    {
        $rules = [
            'name_' . app()->getLocale() => 'required',
            'price' => 'required|numeric',
            'book_amount' => 'numeric|max:5'
        ];
        $messages = [
            'price.greater_than_input' => 'Discount price can not be greater than price.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if ($request->has('service_id')) {
                $id = $request->input('service_id');
                $service = Service::find($id);
            } else {
                $service = new Service;
                $service->photo = DEFAULT_PHOTO;
                $service->token = strtoupper(Str::random(5));
                $service->salt = Str::random(8);
                $service->secure_key = md5($service->salt . '');
            }

            if ($request->hasFile('photo')) {
                $fileName = Str::random(24) . "." . $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->move(ABS_SERVICE_PATH, $fileName);
                $service->photo = $fileName;
            }

            $supportedLocales = \LaravelLocalization::getSupportedLocales();
            $service->user_id = $request->input('user_id');
            foreach ($supportedLocales as $localeCode => $locale) {
                $nameField = 'name_' . $localeCode;
                $descField = 'description_' . $localeCode;
                $service->$nameField = $request->get($nameField);
                $service->$descField = $request->get($descField);
            }
            $service->category_id = $request->input('category_id') ?: 10;
            $service->sub_category_id = $request->input('sub_category_id') ?: 10;
            $service->duration = $request->input('duration');
            $service->price = $request->input('price');
            $service->provide_type = $request->input('provide_type');
            $service->save();

            ServiceOffice::where([
                'user_id' => $service->user_id,
                'service_id' => $service->id,
                'office_id' => $service->office_id
            ], [
                'book_count' => $request->input('book_amount')
            ]);

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Service has been saved successfully')]);
            return redirect()->route('admin.service.index');
        }
    }

    public function delete($id) {
        try {
            Service::find($id)->delete();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Service has been deleted successfully')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.This service has been already used')]);
        }

        return redirect()->route('admin.service.index');
    }

    public function active($service_id) {
        $service = Service::findOrFail($service_id);
        $service->active = 1;
        $service->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Service has been active successfully')]);

        return back();
    }
}
