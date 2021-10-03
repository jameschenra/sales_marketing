<?php
namespace App\Models;

use App\Models\ProfessionCategory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Profession extends Model {
	use SluggableScopeHelpers, Sluggable, ModelFieldLanguageHelper;

    protected $table = 'professions';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'category_id', 'icon',
        'description_en', 'description_it', 'description_es', 'slug',
    ];

    protected $guarded = [];
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
        return $this->belongsTo(ProfessionCategory::class, 'category_id');
    }

    public function users() {
        return $this->hasMany('App\Models\ProfessionByUser', 'profession_id', 'id');
    }
    
    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }
}