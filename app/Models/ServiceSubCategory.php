<?php
namespace App\Models;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class ServiceSubCategory extends Model {
	use SluggableScopeHelpers, Sluggable, ModelFieldLanguageHelper;

    protected $table = 'service_sub_categories';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'category_id', 'icon',
        'description_en', 'description_it', 'description_es',
        'slug',
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
				'source' => 'name_en'
			]
		];
	}

    public function category() {
        return $this->belongsTo(ServiceCategory::class, 'category_id', 'id');
    }

    public function services() {
        return $this->hasMany(Service::class, 'sub_category_id');
    }

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }
    
    public static function getByCatId($categoryId) {
        $nameField = 'name' . ((\App::getLocale() == 'en')?'':\App::getLocale());

        return self::select(
                [
                    'id',
                    "$nameField as name",
                    'icon',
                ]
            )->where(['category_id' => $categoryId])->get();
    }
}
