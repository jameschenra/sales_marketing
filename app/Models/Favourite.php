<?php
namespace App\Models;

use App\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favourites';

    protected $fillable = ['user_id', 'favourite_id', 'note', 'type',];

    const TYPE_PROFESSIONAL = 'professional';
    const TYPE_SERVICE = 'service';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function professional()
    {
        return $this->belongsTo(User::class, 'favourite_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'favourite_id');
    }
}
