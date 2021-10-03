<?php
namespace App\Models;

use App\Models\Office;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceOffice extends Model
{
    protected $table = 'service_offices';

    protected $fillable = [
        'user_id', 'service_id', 'office_id', 'book_count', 'book_consecutively', 'onsite_type',
        'provide_range',
    ];

    const TYPE_ONSITE = 1;
    const TYPE_OFFSITE = 2;
    const TYPE_ONOFFSITE = 3;

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function hasConsecutively() : bool
    {
        return $this->book_consecutively > 1;
    }

    public static function deleteByServiceId($serviceId)
    {
        self::where('service_id', $serviceId)->delete();
    }

    public static function isOffsiteByUserId($userId)
    {
        $offsiteOffice = self::where('user_id', $userId)
            ->where('onsite_type', '<>', ServiceOffice::TYPE_ONSITE)
            ->first();

        return $offsiteOffice !== null;
    }
}
