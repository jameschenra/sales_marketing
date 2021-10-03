<?php
namespace App\Models;

use App\Models\Profession;
use App\Models\ProfessionCategory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class ProfessionCategory extends Model {

	use SluggableScopeHelpers, Sluggable, ModelFieldLanguageHelper;

    protected $table = 'profession_categories';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'description_en', 'description_it', 'description_es', 'icon', 'slug',
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

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }

    public function professions() {
        return $this->hasMany(Profession::class, 'category_id');
    }

    public static function getOrderedList()
    {
        $locale = app()->getLocale();

        $list = self::orderBy('name_' . $locale, 'ASC')
            ->where('name_en', '<>', 'Other Professions')
            ->get();
        $otherProfession = self::where('name_en', 'Other Professions')->first();

        $list->push($otherProfession);

        return $list;
    }

    public static function getWithProfessions()
    {
        $list = self::orderBy('name_' . app()->getLocale())
            ->where('name_en', '<>', 'Other Professions')
            ->with(['professions' => function ($query) {
                return $query->orderBy('name_' . app()->getLocale());
            }])->get();

        $otherProfession = self::where('name_en', 'Other Professions')
            ->with(['professions' => function ($query) {
                return $query->orderBy('name_' . app()->getLocale());
            }])->first();
        
        $list->push($otherProfession);

        return $list;
    }
}
