<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TransactionOfCredit extends Model
{
    protected $table = 'transaction_of_credits';

    protected $fillable = [
        'payment_id',
        'user_id',
        'amount',
        'description',
        'status',
        'response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
