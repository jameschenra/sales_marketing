<?php
namespace App\Models;

use App\Models\Region;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class City extends Model
{
    use SluggableScopeHelpers, Sluggable;

    protected $table = 'cities';

    protected $fillable = ['name', 'country_id', 'region_id', 'latitude', 'longitude'];

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

	public function region()
	{
		return $this->hasOne(Region::class, "id", "region_id");
	}
}
