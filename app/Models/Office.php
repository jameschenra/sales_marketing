<?php
namespace App\Models;

use App\User;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use App\Models\OfficeOpening;
use App\Models\ServiceOffice;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

    public const OPEN_HOUR = 5;

    protected $table = 'offices';

    protected $fillable = [
        'user_id', 'name', 'address_area', 'phone_number', 'country_id',
        'region_id', 'city_id', 'zip_code', 'lat', 'lng', 'address',
        'has_timetable', 'has_calendar', 'holidays',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function opening()
    {
        return $this->hasOne(OfficeOpening::class, 'office_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, "id", "country_id");
    }

    public function region()
    {
        return $this->hasOne(Region::class, "id", "region_id");
    }

    public function city()
    {
        return $this->hasOne(City::class, "id", "city_id");
    }

    public function getAddressAreaAttribute()
    {
        return $this->city->name . ', ' . $this->region->name . ', ' . $this->country->short_name;
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', '
            . $this->zip_code . ', '
            . $this->city->name . ', '
            . $this->country->short_name;
    }

    /**
     * Get weekly holidays
     */
    public function weeklyHolidays()
    {
        if (!$this->relationLoaded('opening')) {
            $this->load('opening');
        }

        $daysStart = ['sun_start', 'mon_start', 'tue_start', 'wed_start', 'thu_start', 'fri_start', 'sat_start'];
        $holidays = [];
        foreach ($daysStart as $index => $dayStart) {
            if (strtolower($this->opening->{$dayStart}) == 'closed') {
                $holidays[] = $index;
            }
        }

        return $holidays;
    }

    public function getFullyBookedDaysFor($serviceId, $date = null)
    {
        if ($date == null) {
            $date = date('m/d/Y');
        }

        $orderDates = Book::select(\DB::raw('SUM(duration) as date_duration, date(book_date) as booked_date'))
            ->where('office_id', $this->id)
            ->where('service_id', $serviceId)
            ->whereIn('status', [0, 3])
            ->where('book_date', '>=', $date)
            ->groupBy(\DB::raw('date(book_date)'))
            ->get();

        $fullyBookedDays = [];
        foreach ($orderDates as $orderDate) {
            $bookedDate = Carbon::createFromFormat('Y-m-d', $orderDate->booked_date);
            $dayOfWeek = strtolower($bookedDate->format('D'));
            if ($this->opening->{$dayOfWeek . '_start'} == 'closed') {
                $fullyBookedDays[] = $bookedDate->format('m/d/Y');
            } else {
                $openHour = Carbon::createFromFormat('H:i', $this->opening->{$dayOfWeek . '_start'});
                $closeHour = Carbon::createFromFormat('H:i', $this->opening->{$dayOfWeek . '_end'});
    
                $sameTimeBookingCount = ServiceOffice::where([
                    'service_id' => $serviceId,
                    'office_id' => $this->id,
                ])->first()->book_count;
    
                $totalAvailableDuration = $closeHour->diffInMinutes($openHour) * $sameTimeBookingCount;
    
                if ($orderDate->date_duration >= $totalAvailableDuration) {
                    $fullyBookedDays[] = $bookedDate->format('m/d/Y');
                }
            }
        }

        return $fullyBookedDays;
    }
}
