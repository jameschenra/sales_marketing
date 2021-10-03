<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Plan extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'plans';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'price', 'type', 'code'
    ];

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }
}
