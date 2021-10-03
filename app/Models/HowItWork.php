<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class HowItWork extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'how_it_works';

    protected $fillable = [
        'title_en', 'title_it', 'title_es', 'content_en', 'content_it', 'content_es',
        'image', 'type', 'status'
    ];

    public function getTitleAttribute()
    {
        return $this->getFieldByLanguage('title');
    }

    public function getContentAttribute()
    {
        return $this->getFieldByLanguage('content');
    }
}
