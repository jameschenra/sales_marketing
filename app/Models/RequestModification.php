<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModification extends Model
{
	public const STATUS_MODIFY_REQUEST = 1;
	public const STATUS_MODIFY_ACCEPT = 2;
	public const STATUS_MODIFY_DENIED = 3;
	public const STATUS_MODIFY_ACCEPT_EXTEND = 4;

    protected $table = 'request_modifications';

    protected $fillable = ['book_id', 'description', 'status', 'extension_date'];

	public function book()
	{
		return $this->belongTo(Country::class, "id", "book_id");
	}
}
