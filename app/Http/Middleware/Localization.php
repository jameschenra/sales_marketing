<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!$user->default_language) {
                if (session('locale')) {
                    $lang = session('locale');
                } else {
                    $lang = $this->getBrowserLanguage(substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2));
                }

                $user->default_language = $lang;
                $user->save();
            }

            app()->setLocale($user->default_language);
        } else {
            // if language not setted set language as browser's
            if (session('locale') == null) {
                $lang = $this->getBrowserLanguage(substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2));
                if ($lang) {
                    app()->setLocale($lang);
                    session(['locale' => $lang]);
                }
            } else {
                app()->setLocale(session('locale'));
            }
        }

        $this->setLanguageArray();

        return $next($request);
    }

    private function getBrowserLanguage($acceptLangauges) {
        $availableLangs = ['en', 'it', 'es'];
        $userLangs = preg_split('/,|;/', $acceptLangauges);

        foreach ($userLangs as $lang) {
            if(in_array($lang, $availableLangs)) {
                return $lang;
            }
        }

        return null;
    }

    private function setLanguageArray()
    {
        $langs = [
            'en' => [
                'locale' => 'en',
                'label' => 'English',
                'icon' => adminAsset('media/svg/flags/226-united-states.svg'),
            ],
            'it' => [
                'locale' => 'it',
                'label' => 'Italiano',
                'icon' => adminAsset('media/svg/flags/013-italy.svg'),
            ],
            'es' => [
                'locale' => 'es',
                'label' => 'Español',
                'icon' => adminAsset('media/svg/flags/016-spain.svg'),
            ],
        ];

        $locale = app()->getLocale();

        if ($locale == 'en') {
            $langArr = [ $langs['en'], $langs['it'], $langs['es'] ];
        } else if ($locale == 'it') {
            $langArr = [ $langs['it'], $langs['en'], $langs['es'] ];
        } else {
            $langArr = [ $langs['es'], $langs['en'], $langs['it'] ];
        }

        view()->composer('*', function ($view) use ($langArr) {

            $view->with('languageList', $langArr);

        });
    }
}
