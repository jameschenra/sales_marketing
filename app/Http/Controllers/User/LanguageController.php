<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{  
    public function setLanguage($locale)
    {
        session(['locale' => $locale]);
        app()->setLocale($locale);

        if (Auth::check()) {
            $user = Auth::user();
            $user->default_language = $locale;
            $user->save();

            session()->flash('language_modified', true);
        }

        return redirect()->back();
    }
}
