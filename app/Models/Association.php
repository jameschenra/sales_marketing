<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Association extends Model
{
    use SluggableScopeHelpers, Sluggable, ModelFieldLanguageHelper;

    protected $table = 'associations';

    protected $fillable = ['name_en', 'name_it', 'name_es'];

    /**
	 * Return the sluggable configuration array for this model.
	 *
	 * @return array
	 */
	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'name'
			]
		];
	}

	public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }
}
