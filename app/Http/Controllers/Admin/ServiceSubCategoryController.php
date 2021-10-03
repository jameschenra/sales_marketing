<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\ServiceSubCategory;
use App\Http\Controllers\Controller;

class ServiceSubCategoryController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Sub Category');
        $param['page_description'] = trans('main.List');
        $param['subCategories'] = ServiceSubCategory::orderBy('name_' . app()->getLocale())->paginate(PAGINATION_SIZE);

        return view('admin.pages.service-sub-category.index')->with($param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Sub Category');
        $param['page_description'] = trans('main.Create');
        $param['categories'] = ServiceCategory::orderBy('name_' . app()->getLocale())->get();

        return view('admin.pages.service-sub-category.form')->with($param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Category');
        $param['page_description'] = trans('main.Edit');
        $param['categories'] = ServiceCategory::orderBy('name_' . app()->getLocale())->get();
        $param['sub_category'] = ServiceSubCategory::find($id);

        return view('admin.pages.service-sub-category.form')->with($param);
    }

    public function store(Request $request)
    {
        $rules = ['name_en' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if ($request->has('sub_category_id')) {
                $id = $request->input('sub_category_id');
                $subCategory = ServiceSubCategory::find($id);
            } else {
                $subCategory = new ServiceSubCategory();
            }
            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $nameField = 'name_' . $localeCode;
                $descField = 'description_' . $localeCode;
                $subCategory->$nameField = $request->input($nameField);
                $subCategory->$descField = $request->input($descField);
            }

            // resluggify
            $subCategory->category_id = $request->input('category_id');
            $subCategory->slug = null;
            $subCategory->save();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Category has been saved successfully')]);

            return redirect()->route('admin.svcsubcat.index');
        }
    }

    public function delete($id) {
        try {
            ServiceSubCategory::find($id)->delete();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Category has been deleted successfully')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.This category has been already used')]);
        }

        return redirect()->back();
    }
}
