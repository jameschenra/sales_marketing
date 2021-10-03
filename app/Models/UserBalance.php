<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    protected $table = 'user_balances';

    protected $fillable = [
        'user_id', 'balance', 'pending_balance', 'access_balance_first'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
