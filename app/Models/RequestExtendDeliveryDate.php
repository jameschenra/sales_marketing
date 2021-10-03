<?php
namespace App\Models;

use App\Models\Region;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class RequestExtendDeliveryDate extends Model
{
	public const STATUS_EXTEND_REQUEST = 1;
	public const STATUS_EXTEND_ACCEPT = 2;
	public const STATUS_EXTEND_DENIED = 3;

    protected $table = 'request_extend_delivery_dates';

    protected $fillable = ['book_id', 'delivery_date', 'status'];

	public function book()
	{
		return $this->belongTo(Country::class, "id", "book_id");
	}
}
