<?php

namespace App\Models;

use App\User;
use App\Models\Book;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class TransactionOfBooking extends Model
{
    const TYPE_NORMAL           = 1;
    const TYPE_FEE              = 2;
    const TYPE_NORMAL_REFUND    = 3;
    const TYPE_FEE_REFUND       = 4;
    const TYPE_CREDIT           = 5;
    const TYPE_WITHDRAW         = 6;

    protected $fillable = [
        'payment_id',
        'transaction_type',
        'sender_id',
        'receiver_id',
        'service_id',
        'book_id',
        'amount',
        'on_hold',
        'currency',
        'params',
        'show_user',
        'refunded',
    ];

    protected $table = 'transaction_of_bookings';

    protected $casts = [
        'params' => 'json',
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y H:i', strtotime($value));
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function addParams($params)
    {
        $this->params = array_merge($this->params, $params);

        $this->save();
    }

    public static function getTotalPendingFee()
    {
        return self::where('receiver_id', '0')
            ->whereRaw('(unix_timestamp(created_at)+345600)>unix_timestamp()')
            ->where('refunded', '0')
            ->sum('amount');
    }

    public static function getTotalAvailableFee()
    {
        return self::where('receiver_id', '0')
            ->whereRaw('(unix_timestamp(created_at)+345600)>unix_timestamp()')
            ->sum('amount');
    }

    public static function getRefundedBySite()
    {
        return self::where(['receiver_id' => '0', 'refunded' => '1'])->sum('amount');
    }

    public static function getTransactionByBookId($bookId)
    {
        return self::where('book_id', $bookId)
            ->where('transaction_type', self::TYPE_NORMAL)
            ->where('on_hold', 1)
            ->first();
    }

    public static function getFeeTransactionByBookId($bookId)
    {
        return self::where('book_id', $bookId)
            ->where('transaction_type', self::TYPE_FEE)
            ->where('on_hold', 1)
            ->first();
    }
}
