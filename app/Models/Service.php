<?php

namespace App\Models;

use App\User;
use App\Models\Book;
use App\Models\Office;
use App\Models\Review;
use App\Models\Feedback;
use App\Models\Favourite;
use App\Models\UserDetail;
use App\Models\ServiceOffice;
use App\Traits\PrettyDuration;
use App\Models\ServiceCategory;
use App\Models\ServiceSubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Service extends Model
{
    use PrettyDuration;

    const MAX_DURATION = 18;
    const MIN_PRICE = 6;
    const MIN_AMOUNT_TO_ADD = 20;

    const MAX_DELIVERY_TIME = 180;
    const MAX_REVISIONS = 20;

    const PROVIDE_ONLINE_TYPE = 1;
    const PROVIDE_OFFLINE_TYPE = 2;

    const PAYMENT_TYPE_ONLINE = 1;
    const PAYMENT_TYPE_ONSITE = 2;
    const PAYMENT_TYPE_ONLINEONSITE = 3;
    const PAYMENT_TYPE_FREE = 4;

    const EXTRA_PRICE_NO = 1;
    const EXTRA_PRICE_FIX = 2;
    const EXTRA_PRICE_KILOMETER = 3;

    const BOOKING_DIRECTLY = 1;
    const BOOKING_CONFIRM = 2;

    use SluggableScopeHelpers,
        Sluggable,
        ModelFieldLanguageHelper;

    protected $table = 'services';

    protected $fillable = [
        'user_id', 'name_en', 'name_it', 'name_es', 'slug', 'category_id', 'sub_category_id', 'photo',
        'description_en', 'description_it', 'description_es', 'provide_online_type', 'online_delivery_time',
        'online_office_id', 'online_book_count', 'online_revision', 'online_file_required', 'duration', 'client_payment_type',
        'booking_confirm', 'price', 'discount_percentage_single', 'discount_percentage_multiple', 'payment_type',
        'extra_price_type', 'extra_price', 'has_video_call', 'keyword', 'count_view',
        'token', 'secure_key', 'salt', 'active', 'review_score', 
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
                'source' => 'locale_name',
            ],
        ];
    }

    protected static function booted()
    {
        static::created(function ($service) {
            $user = $service->user;
            $user->has_service = 1;
            $user->save();

            $userDetail = $user->detail;
            if ($userDetail->profile_wizard_completed != UserDetail::SERVICE_COMPLETED) {
                $userDetail->profile_wizard_completed = UserDetail::SERVICE_COMPLETED;
                $userDetail->save();
            }
        });

        static::deleted(function ($service) {
            $user = $service->user;
            $anyService = Service::where(['user_id' => $user->id])->first();
            if (!$anyService) {
                $user->has_service = 0;
                $user->save();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ServiceSubCategory::class, 'sub_category_id', 'id');
    }

    public function offices()
    {
        return $this->hasMany(ServiceOffice::class, 'service_id', 'id');
    }

    public function onlineOffice()
    {
        return $this->belongsTo(Office::class, 'online_office_id', 'id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'service_id')->orderBy('created_at', 'DESC');
    }

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }

    public function getLocaleNameAttribute()
    {
        $user = User::find($this->user_id);
        $d_name = 'name_' . $user->default_language;

        return $this->$d_name;
    }

    public function getDeliveryPlaceAttribute()
    {
        if ($this->provide_online_type == self::PROVIDE_ONLINE_TYPE) {
            return 'online';
        } else {
            return 'office';
        }
    }

    public function getActualPrice($bookCount = 1)
    {
        return $this->price - $this->getDiscount($bookCount);
    }

    public function getDiscount($bookCount = 1)
    {
        if ($bookCount == 1) {
            if ($this->discount_percentage_single && $this->discount_percentage_single > 0) {
                return ($this->price * $this->discount_percentage_single) / 100;
            }
        } elseif ($bookCount > 1) {
            if ($this->discount_percentage_multiple && $this->discount_percentage_multiple > 0) {
                return ($this->price * $this->discount_percentage_multiple) / 100;
            } else {
                return ($this->price * $this->discount_percentage_single) / 100;
            }
        }

        return 0;
    }

    public function hasConsecutively()
    {
        $hasConsecutively = !! $this->offices
            ->filter(function ($office) {
                return $office->hasConsecutively();
            })->count();

        return $hasConsecutively;
    }

    public function getReviewCount()
    {
        $result = Book::leftJoin('reviews', 'books.review_id', '=', 'reviews.id')
            ->select(DB::raw('COUNT(*) as store_reviews_count'))
            ->where('books.service_id', $this->id)
            ->where('reviews.is_published', 1)
            ->whereNotNull('books.review_id')
            ->get();
        
        return $result[0]->store_reviews_count;
    }

    public function getReviewScore()
    {
        $result = Review::leftJoin('books', 'reviews.book_id', '=', 'books.id')
            ->select(DB::raw('ROUND(AVG(reviews.rate)) as store_average_rate'))
            ->where('books.service_id', $this->id)
            ->where('reviews.is_published', 1)
            ->whereNotNull('books.review_id')
            ->get();

        return $result[0]->store_average_rate;
    }

    public function getFavouriteId()
    {
        $favCount = Favourite::where('favourite_id', $this->id)
            ->where('user_id', auth()->id())
            ->where('type', 'service')
            ->first();

        return $favCount ? $favCount->id : null;
    }

    public function availableSiteStatus()
    {
        $onSiteOffice = ServiceOffice::where('service_id', $this->id)
            ->where('onsite_type', ServiceOffice::TYPE_ONSITE)
            ->first();

        $offSiteOffice = ServiceOffice::where('service_id', $this->id)
            ->where('onsite_type', ServiceOffice::TYPE_OFFSITE)
            ->first();

        $onOffSiteOffice = ServiceOffice::where('service_id', $this->id)
            ->where('onsite_type', ServiceOffice::TYPE_ONOFFSITE)
            ->first();
        
        if ($onOffSiteOffice) {
            return ServiceOffice::TYPE_ONOFFSITE;
        } else {
            if ($onSiteOffice && $offSiteOffice) {
                return ServiceOffice::TYPE_ONOFFSITE;
            } else if ($onSiteOffice) {
                return ServiceOffice::TYPE_ONSITE;
            } else if ($offSiteOffice) {
                return ServiceOffice::TYPE_OFFSITE;
            }
        }

        return null;
    }

    public static function getByUserId($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getAvailableServiceByUserId($userId)
    {
        $services = self::with('user, user.balance')->where('user_id', $userId)
            ->where('active', 1)
            ->where(function ($query) {
                $query->where(function ($q2) {
                    $q2->where('price', 0)->whereHas('user.balance', function ($q) {
                        $q->where('balance', '>', 0);
                    });
                })->orWhere('price', '>', 0);
            })
            ->orderBy('price', 'desc')
            ->get();
                
        $services = $services->filter(function ($item, $key) use($user) {
            if ($item->price == 0){
                if ($user->wallet_balance < MIN_CREDIT_TO_STOP_VIEW_FREE_SERVICE
                    || ($user->unsubscribe_minimum_credit == 0 && $item->price == 0)){
                    return false;
                }
            }

            return true;
        });
    }
}
