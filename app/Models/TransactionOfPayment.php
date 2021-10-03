<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TransactionOfPayment extends Model
{
    protected $table = 'transaction_of_payments';

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_token',
        'data',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
