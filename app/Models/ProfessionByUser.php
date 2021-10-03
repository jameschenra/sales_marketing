<?php
namespace App\Models;

use App\Models\Profession;
use App\Models\ProfessionCategory;
use Illuminate\Database\Eloquent\Model;

class ProfessionByUser extends Model
{

    protected $table = 'profession_by_users';
    protected $fillable = [
        'user_id', 'profession_category_id', 'profession_id'
    ];

    public function profession() {
        return $this->belongsTo(Profession::class, 'profession_id', 'id');
    }

    public function category() {
        return $this->belongsTo(ProfessionCategory::class, 'profession_category_id', 'id');
    }
}
