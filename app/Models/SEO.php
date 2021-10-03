<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    protected $table = 'seos';

    protected $fillable = [
        'key', 'title_en', 'title_es', 'title_it', 'keyword_en', 'keyword_es', 'keyword_it',
        'description_en', 'description_es', 'description_it',
    ];
}
