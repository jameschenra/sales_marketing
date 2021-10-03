<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class CompanyType extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'company_types';

    protected $fillable = ['name_en', 'name_it', 'name_es'];

    public $timestamps = false;

    const TAX_EXTRA_UE = 1;
    const TAX_UE_VAT = 2;
    const TAX_VAT = 3;
    const TAX_EIN = 4;

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }
}
