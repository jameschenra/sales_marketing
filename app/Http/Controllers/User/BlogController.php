<?php

namespace App\Http\Controllers\User;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Models\WorldOfProfessional;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword') ?: '';
        $params = $this->getDefaultParams();

        if (empty($keyword)) {
            $params['posts'] = Post::orderBy('created_at', 'DESC')->with(['user'])->paginate(5);
        } else {
            $result = Post::search($keyword, $request->input('category'));

            $params['posts'] = $result->orderBy('created_at', 'DESC')
                ->with(['user'])
                ->paginate(5);
        }
        
        return view('user.pages.post.search', $params);
    }

    public function detail($slug)
    {
        $params = $this->getDefaultParams();
        $params['posts'] = [Post::findBySlug($slug)];
        $params['is_detail'] = true;

        return view('user.pages.post.search', $params);
    }

    public function showByAuthor($slug)
    {
        $params = $this->getDefaultParams();
        $params['posts'] = Post::getPostByAuthor($slug)->paginate(5);

        return view('user.pages.post.search', $params);
    }

    public function showByCategory($slug)
    {
        $params = $this->getDefaultParams();
        $params['posts'] = Post::getPostByCategory($slug)->paginate(5);

        return view('user.pages.post.search', $params);
    }

    public function allWorldOfProfession()
    {
        $params['world_of_professions'] = WorldOfProfessional::get();
        $params['posts'] = WorldOfProfessional::paginate(5);
        $params['is_world_profession'] = true;

        return view('user.pages.post.search', $params);
    }

    public function detailWorldOfProfession($slug)
    {
        $params['world_of_professions'] = WorldOfProfessional::get();
        $params['posts'] = [WorldOfProfessional::findBySlug($slug)];
        $params['is_world_profession'] = true;
        $params['is_detail'] = true;

        return view('user.pages.post.search', $params);
    }

    private function getDefaultParams()
    {
        $params['post_categories'] = PostCategory::orderBy('name_' . app()->getLocale(), 'ASC')->get();
        $params['cntWop'] = WorldOfProfessional::count();

        return $params;
    }
}
