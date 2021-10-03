<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\SEO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SEOController extends Controller
{
    public $SEO_pages;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->SEO_pages = [
            'default' => ['title' => trans('main.default'), 'vars' =>''],
            'home' => ['title' => trans('main.home'), 'vars' =>''],

            'user.auth.showLogin' => ['title' => trans('main.Login'), 'vars' =>''],
            'user.auth.showSignup' => ['title' => trans('main.Sign Up'), 'vars' =>''],
            'user.book' => ['title' => trans('main.Cart'), 'vars' =>''],
            'user.requests' => ['title' => trans('main.My Requests'), 'vars' =>''],
            'user.messages' => ['title' => trans('main.Messages'), 'vars' =>''],
            'request.create' => ['title' => trans('main.Request a service'), 'vars' =>''],

            'store.search' => ['title' => trans('main.Service').' '.trans('main.Search'), 'vars' =>''],
            'store.detail' => ['title' => trans('main.Service').' '.trans('main.Profile'), 'vars' =>''],
            'store.detail.photo' => ['title' => trans('main.Service').' '.trans('main.Photo'), 'vars' =>''],

            'store.profsearch' => ['title' => trans('main.Professional').' '.trans('main.Search'), 'vars' =>''],
            'store.detailpro' => ['title' => trans('main.Professional Profile'), 'vars' =>''],

            'store.posts' => ['title' => trans('main.Blog'), 'vars' =>''],
            'post.detail' => ['title' => trans('main.Blog').' '.trans('main.Detail'), 'vars' =>''],

            'post.professions' => ['title' => trans('main.World of Professions'), 'vars' =>''],
            'wof.detail' => ['title' => trans('main.World of Professions').' '.trans('main.Detail'), 'vars' =>''],

            'user.contactus' => ['title' => trans('main.Contact and Support'), 'vars' =>''],

            'user.help' => ['title' => trans('main.Help'), 'vars' =>''],
            'user.terms' => ['title' => trans('main.Terms'), 'vars' =>''],
            'user.howitworks' => ['title' => trans('main.howitworks'), 'vars' =>''],
        ];
    }

    public function index()
    {
        $params['page_title'] = trans('main.SEO');
        $params['page_description'] = trans('main.List');

        $params['sections'] = $this->SEO_pages;
        return view('admin.pages.seo.index', $params);
    }

    public function view($name)
    {
        $param['section'] = $this->SEO_pages[$name];
        $param['key'] = $name;

        $param['data'] = '';
        $section = SEO::where('key', $name)->first();
        if($section) $param['data'] = $section;

        return view('admin.pages.seo.view')->with($param);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $section = SEO::where('key', $name)->first();

        if(!$section){
            $section = new SEO;
            $section->key = $name;
        }

        foreach($request->all() as $key => $value){
            if($key != '_token' && $key != 'name') $section->$key = $value;
        }

        $section->save();

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Success')]);

        return redirect()->back();
    }
}
