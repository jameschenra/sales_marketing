<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Terms');
        $param['page_description'] = trans('main.List');
        $param['terms'] = Term::paginate(PAGINATION_SIZE);

        return view('admin.pages.terms.index', $param);
    }

    public function create()
	{
        $param['page_title'] = trans('main.Terms');
        $param['page_description'] = trans('main.Create');
        return view('admin.pages.terms.form', $param);
    }
    
    public function edit($id)
	{
        $param['page_title'] = trans('main.Terms');
        $param['page_description'] = trans('main.Edit');
        $param['term'] = Term::find($id);

        return view('admin.pages.terms.form', $param);
    }
    
    public function store(Request $request) {
		if ($request->has('term_id')){
            $id = $request->input('term_id');
            $term = Term::find($id);
        } else {
            $term = new Term;
        }

        $term->title_en = $request->input('title_en');
        $term->title_it = $request->input('title_it');
        $term->title_es = $request->input('title_es');
        $term->content_en = $request->input('content_en');
        $term->content_it = $request->input('content_it');
        $term->content_es = $request->input('content_es');
        $term->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
    	return redirect()->route('admin.terms.index');
    }

    public function delete($id) {
        try {
            Term::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.Error')]);
        }

        return redirect()->route('admin.terms.index');
    }
}
