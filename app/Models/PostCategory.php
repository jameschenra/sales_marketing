<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PostCategory extends Model
{
    use SluggableScopeHelpers,
        Sluggable,
        ModelFieldLanguageHelper;

    protected $table = 'post_categories';

    protected $fillable = [
        'name_en', 'name_it', 'name_es', 'description_en', 'description_it', 'description_es', 'icon', 'slug',
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
                'source' => 'name_en',
            ],
        ];
    }

    public function getNameAttribute()
    {
        return $this->getFieldByLanguage('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getFieldByLanguage('description');
    }

    public function getCatCount($id)
    {
        return PostSubCategory::where('category_id', $id)->count();

        $prefix = DB::getTablePrefix();
        $tblCat = with(new PostSubCategory)->getTable();
        $sql = "select * from " . $prefix . $tblCat . " where category_id = " . $id;

        $res = DB::select($sql);
        return count($res);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
