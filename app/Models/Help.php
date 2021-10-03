<?php
namespace App\Models;

use App\Models\HelpType;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Help extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'helps';

    protected $fillable = [
        'help_type_id', 'title_en', 'title_it', 'title_es',
        'content_en', 'content_it', 'content_es', 'status'
    ];

    public function getTitleAttribute()
    {
        return $this->getFieldByLanguage('title');
    }

    public function getContentAttribute()
    {
        return $this->getFieldByLanguage('content');
    }

    public function type()
    {
        return $this->hasOne(HelpType::class, 'id', 'help_type_id');
    }
}
