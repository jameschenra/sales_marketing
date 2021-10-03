<?php
namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;

class Offer extends Model
{
    use ModelFieldLanguageHelper;

    protected $table = 'offers';

    protected $fillable = [
        'user_id', 'name_en', 'name_it', 'name_es', 'photo', 
        'description_en', 'description_it', 'description_es',
        'price', 'received', 'expire_at', 'is_review',  
    ];

    public function scopePurchase($query)
    {
        return $query->where('is_review', '=', 0);
    }

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
