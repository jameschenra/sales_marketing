<?php

namespace App;

use App\Models\TFA;
use App\Models\Book;
use App\Models\Service;
use App\Models\Language;
use App\Models\Favourite;
use App\Models\UserDetail;
use App\Models\TransactionOfBooking;
use App\Helpers\PriceHelper;
use App\Models\ServiceOffice;
use App\Models\ProfessionByUser;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;use Illuminate\Support\Facades\DB;
use App\Helpers\ModelFieldLanguageHelper;

class User extends Authenticatable
{
    use Notifiable;
    use SluggableScopeHelpers, Sluggable;
    use ModelFieldLanguageHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'last_name', 'email', 'password', 'phone', 'default_language',
        'namees', 'nameit', 'hourly_rate', 'slug', 'is_active', 'is_suspend',
        'has_service', 'phone_verified', 'payment_verified', 'review_score', 'login_count',
        'last_activity'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function billingInfo()
    {
        return $this->hasOne('App\Models\UserBillingInfo');
    }

    public function balance()
    {
        return $this->hasOne('App\Models\UserBalance');
    }

    public function profsByUser()
    {
        return $this->hasMany(ProfessionByUser::class, 'user_id', 'id');
    }

    public function offices()
    {
        return $this->hasMany('App\Models\Office', 'user_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function serviceOffices()
    {
        return $this->hasMany(ServiceOffice::class, 'user_id');
    }

    public function codes()
    {
        return $this->hasMany(TFA::class, 'user_id', 'id');
    }

    public function hasInvoiceDetails()
    {
        return false;
    }

    public function getPendingBalance()
    {
        $pendingBooks = Book::where('seller_id', $this->id)
            ->where('status', Book::STATUS_PENDING)
            ->where('is_paid_online', 1)
            ->orWhere('status', Book::STATUS_PROVIDED)
            ->get();

        $pendingBalance = 0;
        foreach ($pendingBooks as $book) {
            if ($book->total_amount > 0) {
                $pendingBalance += ($book->total_amount - $book->total_fee);
            }
        }

        return $pendingBalance;
    }

    public function getTotalFees()
    {
        $totalFee = TransactionOfBooking::where('sender_id', $this->id)
            ->where('transaction_type', TransactionOfBooking::TYPE_FEE)
            ->where('refunded', 0)
            ->sum('amount');
        
        return $totalFee;
    }

    public function getTotalWithdraws()
    {
        $withdraws = TransactionOfBooking::where('sender_id', 0)->where('receiver_id', $this->id)->where('service_id', 0)->get();
        $count = 0;
        foreach ($withdraws as $withdraw) {
            $count += $withdraw->amount;
        }
        return $count;
    }

    public function getReviewCount()
    {
        $sql = "SELECT COUNT(*) as reviews_count FROM reviews WHERE user_id = $this->id AND is_published = 1";
        // $sql = "SELECT COUNT(*) as reviews_count FROM reviews WHERE user_id = $this->id";
        $result = DB::select($sql);
        return $result[0]->reviews_count;
    }

    public function getReviewScore()
    {
        $sql = "SELECT ROUND(avg(rate)) as average_rate FROM reviews WHERE user_id = $this->id AND is_published = 1";
        // $sql = "SELECT ROUND(avg(rate)) as average_rate FROM reviews WHERE user_id = $this->id";
        $result = DB::select($sql);
        return $result[0]->average_rate;
    }

    public function getFavouriteId()
    {
        $favourite = Favourite::where('favourite_id', $this->id)
            ->where('user_id', auth()->id())
            ->where('type', Favourite::TYPE_PROFESSIONAL)
            ->first();

        return $favourite ? $favourite->id : null;
    }

    public function getLang($count = 0)
    {
        $locale = app()->getLocale();
        if (!$this->detail->languages) {
            return '';
        }

        $short_langs = explode(",", $this->detail->languages);
        $langs = [];
        foreach ($short_langs as $key => $sl) {
            if ($count > 0) {
                if ($count >= ($key + 1)) {
                    $lang_obj = Language::where('code', $sl)->firstOrFail();
                    $langs[] = $lang_obj['name_' . $locale];
                }
            } else {
                $lang_obj = Language::where('code', $sl)->firstOrFail();
                $langs[] = $lang_obj['name_' . $locale];
            }
        }
        $langs = implode(", ", $langs);
        return $langs;
    }

    /**
     * Number of unread messages
     *
     * @return mixed
     */
    public function unreadCounter()
    {
        return Conversation::whereHas('messages.status', function ($query) {
            $query->where('status', 0)->where('user_id', $this->id);
        })->count();
    }

    /**
     * Filter professionals
     *
     * @param $query
     * @return mixed
     */
    public function scopeProfessional($query)
    {
        return $query->whereNotIn('slug', ['redazione'])
            ->has('services', '>=', 1);
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->name) . ' ' . ucfirst($this->last_name);
    }

    public function getInitialNameAttribute()
    {
        return ucfirst($this->name) . ' ' . strtoupper($this->last_name[0]) . '.';
    }

    public function getDescriptionAttribute()
    {
        $detail = $this->detail;
        return $detail->description ?? '';
    }

    public function getWalletBalanceAttribute()
    {
        return $this->balance->balance;
    }

    public function hasPurchase() {
        $book = Book::where('user_id', $this->id)->first();
        if ($book) {
            return true;
        } else {
            return false;
        }
    }

    public function getIsOnlineAttribute()
    {
        if (!$this->last_activity) {
            return false;
        }

        // if over 8 hrs after logged in
        return (strtotime($this->last_activity) + 8 * 60 * 60) > time();
    }

    public function withdraw($amount, $params = null)
    {
        $this->balance->balance -= $amount;
        if (!$this->balance->save()) {
            throw new Exception("Balance wasn't changed on the company model.");
        }

        return TransactionOfBooking::create([
            'transaction_type' => TransactionOfBooking::TYPE_WITHDRAW,
            'sender_id' => 0,
            'service_id' => 0,
            'receiver_id' => $this->id,
            'amount' => $amount,
            'params' => $params,
        ]);
    }

}
