<?php
namespace App\Models;

use App\User;
use App\Models\PostCategory;
use App\Models\PostByCategory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ModelFieldLanguageHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Post extends Model
{
    use SluggableScopeHelpers,
        Sluggable,
        ModelFieldLanguageHelper;

    protected $table = 'posts';

    protected $fillable = ['user_id', 'category_id', 'title_en', 'title_it', 'title_es', 
        'content_en', 'content_it', 'content_es', 'comment_status', 'comment_count',
        'featured_image', 'slug', 'views', 'unique_views',  
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
                'source' => 'title',
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function categories()
    {
        return $this->hasMany(PostByCategory::class, 'post_id');
    }

    public function getTitleAttribute()
    {
        return $this->getFieldByLanguage('title');
    }

    public function getContentAttribute($value)
    {
        return $this->getFieldByLanguage('content');
    }

    public function getMinContentAttribute()
    {
        return substr(strip_tags(html_entity_decode($this->content)), 0, 300) . '...';
    }

    private function getUserId($slug)
    {
        if ($user = User::findBySlug($slug)) {
            return $user->id;
        }

        return $user;
    }

    public function scopeGetPostByAuthor($query, $slug)
    {
        if ($slug == 'admin') {
            return static::where('user_id', 0);
        }

        return static::where('user_id', $this->getUserId($slug));
    }

    public function scopeGetPostByCategory($query, $slug)
    {
        $tblPostCategory = with(new PostCategory)->getTable();

        $result = $query->select($this->table . '.*')
            ->leftJoin($tblPostCategory, $tblPostCategory . '.id', '=', $this->table . '.category_id')
            ->where($tblPostCategory . '.slug', $slug);
        return $result;
    }

    public function scopeSearch($query, $keyword, $catId = null)
    {
        $tblPostCategory = with(new PostCategory)->getTable();

        $result = $query->select($this->table . '.*')
            ->leftJoin($tblPostCategory,  $tblPostCategory . '.id', '=', $this->table . '.category_id');

        if ($keyword != '') {
            $result->where(function ($query) use ($keyword, $tblPostCategory) {
                $query->where($tblPostCategory . '.name_en', 'like', '%' . $keyword . '%')
                    ->orWhere($tblPostCategory . '.name_it', 'like', '%' . $keyword . '%')
                    ->orWhere($tblPostCategory . '.name_es', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.title_en', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.title_it', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.title_es', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.content_en', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.content_it', 'like', '%' . $keyword . '%')
                    ->orWhere($this->table . '.content_es', 'like', '%' . $keyword . '%');
            });


            if ($catId != null) {
                $result = $result->where($tblPostCategory . '.id', $catId);
            }
        }

        return $result;
    }
}
