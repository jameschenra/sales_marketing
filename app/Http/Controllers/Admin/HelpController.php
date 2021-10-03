<?php

namespace App\Http\Controllers\Admin;

use App\Models\Help;
use App\Models\HelpType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Help');
        $param['page_description'] = trans('main.List');
        $param['helps'] = Help::paginate(PAGINATION_SIZE);

        return view('admin.pages.help.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Help');
        $param['page_description'] = trans('main.Create');
        $param['types'] = HelpType::get();

        return view('admin.pages.help.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Help');
        $param['page_description'] = trans('main.Edit');
        $param['types'] = HelpType::get();
        $param['help'] = Help::find($id);

        return view('admin.pages.help.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('help_id')){
            $id = $request->input('help_id');
            $help = Help::find($id);
        } else {
            $help = new Help;
        }

        $help->help_type_id = $request->input('help_type_id');
        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
            $titleField = 'title_' . $localeCode;
            $contentField = 'content_' . $localeCode;
            $help->$titleField = $request->input($titleField);
            $help->$contentField = $request->input($contentField);
        }

        $help->status = 0;
        $help->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.help.index');
    }

    public function delete($id) {
        try {
            Help::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
