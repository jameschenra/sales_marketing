<?php

namespace App\Http\Controllers\User;

use Image;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PostRequest;

class PostController extends Controller
{

    public function myList()
    {
        $param['posts'] = Post::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(PAGINATION_SIZE);
        $param['user'] = auth()->user();

        return view('user.pages.post.index', $param);
    }

    public function create()
    {
        $user = auth()->user();
        $allServices = Service::where('user_id', auth()->id())->get();
        if ($allServices->count() == 0) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.need atleast one service')]);
            return redirect()->route('user.post.mylist');
        }

        $param['categories'] = PostCategory::orderBy('name_' . app()->getLocale(), 'ASC')->get();
        $param['dl'] = $user->default_language ? $user->default_language : app()->getLocale();

        return view('user.pages.post.form')->with($param);
    }

    public function edit($id) {
        $param['post'] = Post::find($id);
        if($param['post']->user_id != auth()->id()) {
            session('alert', ['type' => 'danger', 'msg' => trans('main.Error')]);
            return redirect()->back();
        }

        $user = auth()->user();
        $param['categories'] = PostCategory::orderBy('name_' . app()->getLocale(), 'ASC')->get();
        $param['dl'] = $user->default_language ? $user->default_language : app()->getLocale();

        return view('user.pages.post.form')->with($param);
    }

    public function store(PostRequest $request)
    {
        if ($request->has('post_id')) {
            $id = $request->get('post_id');
            $post = Post::find($id);
        } else {
            $post = new Post;
        }

        if ($request->hasFile('featured_image')) {
            $photoFile = $request->file('featured_image');
            $img = Image::make($photoFile);
            $img->orientate();

            if ($img->width() > 1200) {
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $fileName = Str::random(24) . "." . $photoFile->getClientOriginalExtension();
            $file = ABS_POST_PATH . "/" . $fileName;
            $img->save($file, 80, 'jpg');

            $post->featured_image = $fileName;
        } else {
            if (!$post->featured_image) {
                $post->featured_image = 'default-image-blog-post.jpg';
            }
        }

        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $titleField = 'title_' . $localeCode;
            $contentField = 'content_' . $localeCode;
            $post->$titleField = $request->input($titleField);
            $post->$contentField = StringHelper::filterContactInfos($request->get($contentField));
        }

        $post->user_id = auth()->id();
        $post->category_id = $request->input('category_id');
        $post->slug = null;
        $post->save();

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Post has been saved successfully')]);

        return redirect()->route('user.post.mylist');
    }

    public function delete($id) {
        try {
            Post::find($id)->delete();
            session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Post has been deleted successfully')]);
        } catch (\Exception $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.This post has been already used')]);
        }

        return redirect()->route('user.post.mylist');
    }
}
