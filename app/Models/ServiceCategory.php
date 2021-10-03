<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class ServiceCategory extends Model
{

    use SluggableScopeHelpers, Sluggable, ModelFieldLanguageHelper;

    protected $table = 'service_categories';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'icon', 'image',
        'description_en', 'description_it', 'description_es',
        'slug', 'is_other',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name_en',
            ],
        ];
    }

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }

    public function subCategories()
    {
        return $this->hasMany('App\Models\ServiceSubCategory', 'category_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\Service', 'category_id');
    }

    public static function getOrderByName()
    {
        return self::orderBy('name_' . app()->getLocale())->get();
    }

    public static function getWithSubCategory()
    {
        $locale = app()->getLocale();
        return self::orderBy('name_' . $locale)
            ->with(['subCategories' => function ($query) use($locale) {
                return $query->orderBy('name_' . $locale);
            }])->get();
    }

    public static function getImageArray()
    {
        $cats = self::select('id', 'image')->pluck('image', 'id')->toArray();

        return $cats;
    }
}
