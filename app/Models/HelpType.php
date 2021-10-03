<?php
namespace App\Models;

use App\Models\Help;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class HelpType extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'help_types';

    protected $fillable = ['name_en', 'name_it', 'name_es'];

    public function getNameAttribute() {
        return $this->getFieldByLanguage('name');
    }
    
    public function help_contents()
    {
        return $this->hasMany(Help::class, 'help_type_id', 'id');
    }
}
