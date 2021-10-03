<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Models\PostByCategory;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Post');
        $param['page_description'] = trans('main.List');
        $param['posts'] = Post::where('user_id', '<>', 0)->paginate(PAGINATION_SIZE);

        return view('admin.pages.post.index', $param);
    }

    public function create()
    {
        $param['page_title'] = trans('main.Post');
        $param['page_description'] = trans('main.Create');
        $param['categories'] = PostCategory::all();
        return view('admin.pages.post.form')->with($param);
    }

    public function edit($id)
    {
        $param['page_title'] = trans('main.Post');
        $param['page_description'] = trans('main.Edit');
        $param['post'] = Post::find($id);
        $param['categories'] = PostCategory::all();
        return view('admin.pages.post.form')->with($param);
    }

    public function store(Request $request)
    {
        $rules = ['title' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if ($request->has('post_id')) {
                $id = $request->input('post_id');
                $post = Post::find($id);
                $post->user_id = $post->user_id;
            } else {
                $post = new Post;
                $post->user_id = 0;
            }

            if ($request->hasFile('featured_image')) {
                $filename = str_random(24) . "." . $request->file('featured_image')->getClientOriginalExtension();
                $request->file('featured_image')->move(SUB_DIR . ABS_POST_PATH, $filename);
                $post->featured_image = $filename;
            }

            foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $nameCtrl = 'title' . (($localeCode == 'en') ? '' : $localeCode);
                $contentCtrl = 'content' . (($localeCode == 'en') ? '' : $localeCode);
                $post->$nameCtrl = $request->input($nameCtrl);
                $post->$contentCtrl = $request->input($contentCtrl);
            }

            $post->save();

            PostByCategory::where('post_id', $post->id)->delete();
            if ($request->has('category')) {
                foreach ($request->input('category') as $categoryId) {
                    $postSubCategory = new PostByCategory;
                    $postSubCategory->post_id = $post->id;
                    $postSubCategory->category_id = $categoryId;
                    $postSubCategory->save();
                }
            }

            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Post has been saved successfully')]);

            return redirect()->route('admin.post.index');
        }
    }

    public function delete($id) {
        try {
            Post::find($id)->delete();
            session()->flash('message', ['type' => 'success', 'msg' => trans('main.Post has been deleted successfully')]);
        } catch (\Exception $ex) {
            session()->flash('message', ['type' => 'danger', 'msg' => trans('main.This post has been already used')]);
        }

        return redirect()->route('admin.post.index');
    }
}
