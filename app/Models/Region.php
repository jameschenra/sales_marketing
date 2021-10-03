<?php
namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Region extends Model
{
    use SluggableScopeHelpers, Sluggable;

    protected $table = 'regions';

    protected $fillable = ['name', 'code', 'country_id'];

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

	public function country()
	{
		return $this->hasOne(Country::class, "id", "country_id");
	}

	public static function getOrderByName()
	{
		return self::orderBy('name');
	}
}
