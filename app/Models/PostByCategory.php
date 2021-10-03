<?php
namespace App\Models;

use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Model;

class PostByCategory extends Model
{
    protected $table = 'post_by_categories';

    protected $fillable = ['post_id', 'category_id'];

    public function category()
    {
    	return $this->belongsTo(PostCategory::class, 'category_id');
    }  
}
