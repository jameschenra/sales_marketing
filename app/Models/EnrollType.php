<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class EnrollType extends Model
{
    use ModelFieldLanguageHelper;

    const PROFESSIONAL_ORDER = 1;
    const PROFESSIONAL_ASSOCIATION = 2;
    const PROFESSIONAL_INSTITUTE = 3;
    const NOT_ENROLLED = 4;

    protected $table = 'enroll_types';

    protected $fillable = [
        'name_en', 'name_it', 'name_es',
    ];

    public $timestamps = false;

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }
}
