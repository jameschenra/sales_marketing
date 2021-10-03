<?php

namespace App\Http\ViewComposers;

use App\Models\SEO;
use Illuminate\View\View;

class SEODataComposer
{
    /**
     * SeoDataComposer constructor.
     */
    public function __construct()
    {}

    /**
     * Compose seo data to the view
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with($this->getSeoData());
    }

    /**
     * Get SEO Data
     *
     * @return array
     */
    private function getSeoData()
    {
        $locale = app()->getLocale();

        $routeName = app()->request->route()->getName();

        if($routeName == 'user.post.detail' && isset($post_id)) {
            return $this->prepareSeoData(SEO::where('key', 'post.detail@'.$post_id)->first(), $locale);
        }

        if($routeName == 'wof.detail' && isset($post_id)) {
            return $this->prepareSeoData(SEO::where('key', 'wof.detail@'.$post_id)->first(), $locale);
        }

        if(!isset($seo) && $seo = SEO::where('key', $routeName)->first()) {
            return $this->prepareSeoData($seo, $locale);
        }

        return $this->prepareSeoData(SEO::where('key', 'default')->first(), $locale);
    }

    /**
     * @param $seo
     * @return array
     */
    private function prepareSeoData($seo)
    {
        $locale = app()->getLocale();

        return [
            'title_sl'       => $seo->{'title_'. $locale},
            'keyword_sl'     => $seo->{'keyword_'. $locale},
            'description_sl' => $seo->{'description_'. $locale},
        ];
    }
}