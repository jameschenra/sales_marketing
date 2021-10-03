<?php

namespace App\Http\Controllers\Admin;

use App\Models\WorldOfProfessional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class WorldofprofessionController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.World Of Professional');
        $param['page_description'] = trans('main.List');
        $param['world_of_professionals'] = WorldOfProfessional::paginate(PAGINATION_SIZE);

        return view('admin.pages.world-of-professional.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.World Of Professional');
        $param['page_description'] = trans('main.Create');

        return view('admin.pages.world-of-professional.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.World Of Professional');
        $param['page_description'] = trans('main.Edit');
        $param['model'] = WorldOfProfessional::find($id);

        return view('admin.pages.world-of-professional.form', $param);
    }
    
    public function store(Request $request) {
        $rules = [
            'title_en' => 'required',
            'title_it' => 'required',
            'title_es' => 'required',
            'content_en' => 'required',
            'content_it' => 'required',
            'content_es' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

		if ($request->has('model_id')){
            $id = $request->input('model_id');
            $model = WorldOfProfessional::find($id);
        } else {
            $model = new WorldOfProfessional;
        }

        if ($request->hasFile('photo')) {
            $filename = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(ABS_HOWITWORKS_PATH, $filename);
            $model->image = $filename;
        }

        $model->title_en = $request->input('title_en');
        $model->title_it = $request->input('title_it');
        $model->title_es = $request->input('title_es');
        $model->content_en = $request->input('content_en');
        $model->content_it = $request->input('content_it');
        $model->content_es = $request->input('content_es');
        $model->slug = null;
        $model->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.worldofprofession.index');
    }

    public function delete($id) {
        try {
            WorldOfProfessional::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
