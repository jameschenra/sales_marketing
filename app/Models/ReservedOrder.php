<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReservedOrder extends Model
{
    protected $table = 'reserved_orders';

    protected $fillable = [
        'service_id', 'office_id', 'book_date',
    ];

    public static function getByServiceOffice($serviceId, $officeId)
    {
        return self::where(
                [
                    ['service_id', $serviceId],
                    ['office_id', $officeId]
                ]
            )->where('created_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())
            ->groupBy('book_date')
            ->select(\DB::raw("DATE_FORMAT(str_to_date(book_date, '%d/%m/%Y'), '%d/%m/%Y') AS book_date"))
            ->pluck('book_date')
            ->toArray();
    }

    public static function isReservedInFiveMins($serviceId, $officeId, $bookDate)
    {
        $reserved_order = self::where(
            [
                ['service_id', $serviceId],
                ['office_id', $officeId],
                ['book_date', $bookDate],
            ]
        )->where('created_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())
        ->first();

        if ($reserved_order) {
            return true;
        } else {
            return false;
        }
    }
}
