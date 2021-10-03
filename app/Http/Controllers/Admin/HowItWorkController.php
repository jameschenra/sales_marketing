<?php

namespace App\Http\Controllers\Admin;

use App\Models\HowItWork;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HowItWorkController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.howitworks');
        $param['page_description'] = trans('main.List');
        $param['howitworks'] = HowItWork::get();

        return view('admin.pages.how-it-works.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Help Type');
        $param['page_description'] = trans('main.Create');

        return view('admin.pages.how-it-works.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Help Type');
        $param['page_description'] = trans('main.Edit');
        $param['model'] = HowItWork::find($id);

        return view('admin.pages.how-it-works.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('model_id')){
            $id = $request->input('model_id');
            $howitwork = HowItWork::find($id);
        } else {
            $howitwork = new HowItWork;
        }

        if ($request->hasFile('photo')) {
            $filename = str_random(24) . "." . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(ABS_HOWITWORKS_PATH, $filename);
            $howitwork->image = $filename;
        }

        $howitwork->title_en = $request->input('title_en');
        $howitwork->title_it = $request->input('title_it');
        $howitwork->title_es = $request->input('title_es');
        $howitwork->type = $request->input('type');
        $howitwork->content_en = $request->input('content_en');
        $howitwork->content_it = $request->input('content_it');
        $howitwork->content_es = $request->input('content_es');
        $howitwork->status = 0;
        $howitwork->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.howitworks.index');
    }

    public function delete($id) {
        try {
            HowItWork::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
