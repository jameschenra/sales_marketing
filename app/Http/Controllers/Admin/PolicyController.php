<?php

namespace App\Http\Controllers\Admin;

use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Policy');
        $param['page_description'] = trans('main.List');
        $param['policies'] = Policy::paginate(PAGINATION_SIZE);

        return view('admin.pages.policy.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Policy');
        $param['page_description'] = trans('main.Create');
        return view('admin.pages.policy.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Policy');
        $param['page_description'] = trans('main.Edit');
        $param['policy'] = Policy::find($id);

        return view('admin.pages.policy.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('policy_id')){
            $id = $request->input('policy_id');
            $policy = Policy::find($id);
        } else {
            $policy = new Policy;
        }

        $policy->title_en = $request->input('title_en');
        $policy->title_it = $request->input('title_it');
        $policy->title_es = $request->input('title_es');
        $policy->content_en = $request->input('content_en');
        $policy->content_it = $request->input('content_it');
        $policy->content_es = $request->input('content_es');
        $policy->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.policy.index');
    }

    public function delete($id) {
        try {
            Policy::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->route('admin.policy.index');
    }
}
