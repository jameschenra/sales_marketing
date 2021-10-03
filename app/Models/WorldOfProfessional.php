<?php
namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class WorldOfProfessional extends Model
{
    use SluggableScopeHelpers,
        Sluggable,
        ModelFieldLanguageHelper;

    protected $table = 'world_of_professionals';

    protected $fillable = ['title_en', 'title_it', 'title_es', 'content_en', 'content_it', 'content_es',
        'slug', 'image', 'unique_views', 'views'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title_en',
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTitleAttribute()
    {
        return $this->getFieldByLanguage('title');
    }

    public function getContentAttribute()
    {
        return $this->getFieldByLanguage('content');
    }

    public function getMinContentAttribute()
    {
        return substr(strip_tags(html_entity_decode($this->content)), 0, 150);
    }
}
