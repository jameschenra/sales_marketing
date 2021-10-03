<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ProfessionCategory;
use App\Http\Controllers\Controller;

class ProfSubCategoryController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Profession');
        $param['page_description'] = trans('main.List');
        $param['professions'] = Profession::orderBy('name_en')->paginate(PAGINATION_SIZE);

        return view('admin.pages.profession.index')->with($param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Profession');
        $param['page_description'] = trans('main.Create');
        $param['prof_categories'] = ProfessionCategory::orderBy('name_en')->get();

        return view('admin.pages.profession.form')->with($param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Category');
        $param['page_description'] = trans('main.Edit');
        $param['profession'] = Profession::find($id);
        $param['prof_categories'] = ProfessionCategory::orderBy('name_en')->get();

        return view('admin.pages.profession.form')->with($param);
    }

    public function store(Request $request)
    {
        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $nameField = 'name_' . $localeCode;
            $inputval = $request->input($nameField);
            if (!empty($inputval)) {
                $checkProf = Profession::where($nameField, $inputval)->first();
            }

            if (isset($checkProf->id) && !$request->has('profession_id')) {
                return redirect()->back()->withErrors(['msg' => 'main.Profession Already Added']);
            }
        }
        $rules = ['name_' . app()->getLocale() => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            if ($request->has('profession_id')) {
                $id = $request->input('profession_id');
                $profession = Profession::find($id);
            } else {
                $profession = new Profession();
            }
            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $nameField = 'name_' . $localeCode;
                $descCtrl = 'description_' . $localeCode;
                $profession->$nameField = $request->input($nameField);
                $profession->$descCtrl = $request->input($descCtrl);
            }

            // resluggify
            $profession->category_id = $request->input('category_id');
            $profession->slug = null;
            $profession->save();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Profession has been saved successfully')]);

            return redirect()->route('admin.prof.index');
        }
    }

    public function delete($id)
    {
        try {
            Profession::find($id)->delete();

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Profession has been deleted successfully')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.This profession has been already used')]);
        }

        return redirect()->route('admin.prof.index');
    }
}
