<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Country extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'countries';

    protected $fillable = [
        'iso2', 'iso3', 'short_name_en', 'short_name_it', 'short_name_es',
        'long_name_en', 'long_name_it', 'long_name_es', 'numcode', 'un_member',
        'calling_code', 'cctld'
    ];

    public $timestamps = false;

    const COUNTRY_ITALY = 109;

    public function getShortNameAttribute()
    {
        return $this->getFieldByLanguage('short_name');
    }

    public static function getOrderByShortName()
    {
        return self::orderBy('short_name_' . app()->getLocale())->get();
    }
}
