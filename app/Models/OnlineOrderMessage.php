<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineOrderMessage extends Model
{
    protected $table = 'online_order_messages';

    protected $fillable = ['book_id', 'buyer_id', 'seller_id', 'file_name', 'file_path', 'message'];
}
