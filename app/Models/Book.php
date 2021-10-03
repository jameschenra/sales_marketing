<?php
namespace App\Models;

use App\User;
use DateTime;
use App\Models\Office;
use App\Models\Service;
use App\Traits\PrettyDuration;
use App\Models\OnlineOrderMessage;
use Illuminate\Support\Facades\DB;
use App\Models\RequestModification;
use App\Models\TransactionOfBooking;
use Illuminate\Database\Eloquent\Model;
use App\Models\RequestExtendDeliveryDate;

class Book extends Model
{
    use PrettyDuration;

    protected $table = 'books';

    protected $fillable = ['user_id', 'seller_id', 'service_id', 'provide_online_type', 'booking_confirm',
        'status', 'notify_status', 'payment_id', 'book_date', 'delivery_date',
        'accepted_date', 'duration', 'number_of_booking', 'price', 'discount', 'fee', 'total_fee', 'total_amount',
        'txn_id', 'user_address', 'office_id','online_revision', 'message', 'options', 'is_paid_online',
        'payment_type', 'review_id', 'send_invitation_for_review', 'is_result_provided', 'deleted_by',
    ];

    public const STATUS_PENDING = 0;
    public const STATUS_PROVIDED = 1;
    public const STATUS_CANCEL = 2;
    public const STATUS_WAIT_CONFIRM = 3;
    public const STATUS_COMPLETED = 4;

    public const PAID_PAYPAL = 1;
    public const PAID_CREDIT = 2;
    public const PAID_OFFICE = 3;
    public const PAID_FREE = 4;

    public const NOTIFY_STATUS_NONE = 0;
    public const NOTIFY_STATUS_REQUEST_CONFIRM = 1;
    public const NOTIFY_STATUS_AUTO_ACCEPT = 2;

    public function review()
    {
        return $this->hasOne('App\Models\Review', 'book_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function onlineOrderMessages()
    {
        return $this->hasMany(OnlineOrderMessage::class, 'book_id', 'id');
    }

    public function getPaidTypeAttribute($value)
    {
        if ($this->price == 0) {
            return self::PAID_FREE;
        }

        if ($this->is_paid_online != 1) {
            return self::PAID_OFFICE;
        }

        return $this->payment_type;
    }

    public function completeTransaction()
    {
        $bookingTransaction = TransactionOfBooking::where([
            ['transaction_type' => TransactionOfBooking::TYPE_NORMAL],
            ['sender_id', '=', $this->user_id],
            ['receiver_id', '=', $this->seller_id],
            ['on_hold', '=', 1],
        ])->first();

        if ($bookingTransaction) {
            $bookingTransaction->on_hold = 0;
            $bookingTransaction->save();
        }

        $this->status = self::STATUS_PROVIDED;
        $this->save();
    }

    public function clientTransaction()
    {
        return $this->hasOne(TransactionOfBooking::class, 'book_id', 'id')
            ->where('receiver_id', $this->seller_id);
    }

    public function feeTransaction()
    {
        return $this->hasOne(TransactionOfBooking::class, 'book_id', 'id')
            ->where('receiver_id', 0);
    }

    public function refundAvailable()
    {
        return ($this->status == self::STATUS_PENDING)
            && ((strtotime($this->book_date) - time()) > 24 * 60 * 60)
            && (($this->user->balance->balance) >= ($this->number_of_booking * $this->price));
    }

    public function isBeforeBook24Hrs()
    {
        return (strtotime($this->book_date) - time()) > (24 * 60 * 60);
    }

    public function cancellableByUser()
    {
        // return ($this->status == self::STATUS_PENDING || $this->status == self::STATUS_WAIT_CONFIRM)
        //     && ((strtotime($this->book_date) - time()) > 24 * 60 * 60);

        return ($this->status == self::STATUS_PENDING || $this->status == self::STATUS_WAIT_CONFIRM);
    }

    public function getStatusText()
    {
        if ($this->status == 1) {
            return "Complete";
        } else if ($this->status == 2) {
            return "Cancelled";
        } else {
            return "Pending";
        }
    }

    public function getOptionAttribute()
    {
        return unserialize($this->options);
    }

    public function getBookDayAttribute()
    {
        $bookDateTime = new DateTime($this->book_date);
        return $bookDateTime->format('d-m-Y');
    }

    public function getBookTimeAttribute()
    {
        $bookDateTime = new DateTime($this->book_date);
        return $bookDateTime->format('H:i');
    }

    public function getDeliveryDateFormattedAttribute()
    {
        $deliveryDate = new DateTime($this->delivery_date);
        return $deliveryDate->format('d-m-Y');
    }

    public function getExtendRequestAttribute()
    {
        return RequestExtendDeliveryDate::where(['book_id' => $this->id])
			->latest('id')
			->first();
    }

    public function getModificationRequestAttribute()
    {
        return RequestModification::where(['book_id' => $this->id])
			->latest('id')
			->first();
    }

    public static function getBalanceNotAvailable()
    {
        return self::select(DB::raw('sum(number_of_booking*price) as total_not_available'))
            ->whereRaw('(unix_timestamp(created_at)+345600)>unix_timestamp()')
            ->where('deleted_by', NULL)
            ->get();
    }
}
