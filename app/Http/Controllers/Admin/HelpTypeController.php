<?php

namespace App\Http\Controllers\Admin;

use App\Models\HelpType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpTypeController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Help Type');
        $param['page_description'] = trans('main.List');
        $param['types'] = HelpType::get();

        return view('admin.pages.help-type.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Help Type');
        $param['page_description'] = trans('main.Create');

        return view('admin.pages.help-type.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Help Type');
        $param['page_description'] = trans('main.Edit');
        $param['type'] = HelpType::find($id);

        return view('admin.pages.help-type.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('type_id')){
            $id = $request->input('type_id');
            $type = HelpType::find($id);
        } else {
            $type = new HelpType;
        }

        $type->name_en = $request->input('name_en');
        $type->name_it = $request->input('name_it');
        $type->name_es = $request->input('name_es');
        $type->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.helptype.index');
    }

    public function delete($id) {
        try {
            HelpType::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
