<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Language extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'languages';

    public $timestamps = false;

    protected $fillable = [
        'bibliographical', 'terminological', 'code', 'name_en', 'name_it', 'name_es',
    ];

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public static function getOrderByName()
    {
        return self::orderBy('name_' . app()->getLocale())->get();
    }
}
