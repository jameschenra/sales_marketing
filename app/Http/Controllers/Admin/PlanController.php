<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Plan');
        $param['page_description'] = trans('main.List');
        $param['plans'] = Plan::paginate(PAGINATION_SIZE);

        return view('admin.pages.plan.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Plan');
        $param['page_description'] = trans('main.Create');

        return view('admin.pages.plan.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Plan');
        $param['page_description'] = trans('main.Edit');
        $param['plan'] = Plan::find($id);

        return view('admin.pages.plan.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('plan_id')){
            $id = $request->input('plan_id');
            $plan = Plan::find($id);
        } else {
            $plan = new Plan;
        }

        $plan->name_en = $request->input('name_en');
        $plan->name_it = $request->input('name_it');
        $plan->name_es = $request->input('name_es');
        $plan->price = $request->input('price');
        $plan->code = $request->input('code');
        $plan->type = $request->input('type');
        $plan->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.plan.index');
    }

    public function delete($id) {
        try {
            Plan::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->back();
    }
}
