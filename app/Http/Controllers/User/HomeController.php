<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Post;
use App\Models\Term;
use App\Models\Policy;
use App\Enums\UserType;
use App\Models\Service;
use App\Models\HelpType;
use App\Models\HowItWork;
use App\Models\Profession;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;
use App\Models\WorldOfProfessional;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $locale = app()->getLocale();
        $lang = ($user && $user->default_lang) ? $user->default_lang : $locale;

        $param['page_title'] = 'Home';
        $param['page_description'] = 'Weredy homepage.';
        $param['locale'] = $locale;

        $param['categories'] = ServiceCategory::orderBy('name_' . $locale, 'ASC')
            ->whereNotIn('name_en', ['Other'])
            ->limit(6)
            ->get();

        $param['posts'] = Post::orderBy('created_at', 'DESC')->limit(6)->get();
        $param['wofs'] = WorldOfProfessional::orderBy(DB::raw('RAND()'))->take(6)->get();

        if ($user && $user->login_count == 1) {
            $param['is_first_login'] = true;
            $user->login_count = 2;
            $user->save();
        } else {
            $param['is_first_login'] = false;
        }

        return view('user.pages.home.home', $param);
    }

    public function help()
    {
        $param['help_types'] = HelpType::with('help_contents')->get();

        return view('user.pages.help', $param);
    }

    public function showPrivacyPolicy(Request $request)
    {
        $policy = Policy::first();
        return view('user.pages.privacy.privacy-policy', compact('policy'));
    }

    public function howItWorks()
    {
        $howItWorks = HowItWork::where('type', 'User')->get();
        return view('user.pages.home.how-it-works', compact('howItWorks'));
    }

    public function professionalHowItWorks()
    {
        $categories = ServiceCategory::getOrderByName();
        $howItWorks = HowItWork::where('type', 'Professional')->get();
        return view('user.pages.home.how-it-works-professional', compact('howItWorks', 'categories'));
    }

    public function terms()
    {
        $terms = Term::get();
        return view('user.pages.home.terms', compact('terms'));
    }

    public function cookiePolicy()
    {
        $cookiePolicy = Policy::whereId(100)->first();
        return view('user.pages.privacy.cookie-policy', compact('cookiePolicy'));
    }
}
