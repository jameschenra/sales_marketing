<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';

    protected $fillable = [
        'user_id', 'agree_privacy', 'agree_data', 'agree_update',
    ];

    public function user() {
        $this->belongTo('App\User');
    }
}
