<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TransactionOfWithdraw extends Model
{
    protected $table = 'transaction_of_withdraws';

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
