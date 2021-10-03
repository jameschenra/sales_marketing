<?php
namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Loyalty extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'loyalties';

    protected $fillable = [
        'user_id', 'name_en', 'name_it', 'name_es', 'photo', 'count_stamp',
        'description_en', 'description_it', 'description_es'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }
}
