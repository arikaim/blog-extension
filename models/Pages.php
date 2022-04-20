<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Blog\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Blog\Models\Posts;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\SoftDelete;

/**
 * Pages model class
 */
class Pages extends Model  
{
    use Uuid,
        Find,
        Slug,
        Status,
        DateCreated,
        DateUpdated,
        SoftDelete,
        UserRelation;
       
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = [
        'name',       
        'status',
        'slug',
        'date_created',
        'date_updated',
        'date_deleted',
        'user_id'
    ];
   
    public $timestamps = false;
    
    /**
     * Slug source column
     *
     * @var string
     */
    protected $slugSourceColumn = 'name';
    
    /**
     * Posts relation
     *
     * @return Relation|null
     */  
    public function posts()
    {
        return $this->hasMany(Posts::class,'page_id');
    }
    
    /**
     * Get published posts
     * 
     * @return Builder
     */
    public function getPublishedPosts()
    {
        return $this->posts()->where('status','=',1);
    }

    /**
     * Get page url prefix
     *
     * @return string
     */
    public static function getUrlPrefix()
    {
        return '/blog/page/';
    }

    /**
     * Mutator (get) for url attribute.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    /**
     * Get page url
     *
     * @param string|integer|null $name
     * @param boolean $full
     * @param boolean $withLanguagePath
     * @return string
     */
    public function getUrl($name = null)
    {
        $model = (empty($name) == true) ? $this : $this->findPage($name);
       
        return Self::getUrlPrefix() . $model->slug;
    }

    /**
     * Soft delete all posts
     *
     * @return boolean
     */
    public function softDeletePosts(): bool
    {
        $errors = 0;
        $posts = $this->posts()->get();
        foreach ($posts as $post) {
            $result = $post->softDelete();
            $errors += ($result !== true) ? 1 : 0; 
        }
      
        return ($errors == 0);
    }

    /**
     * Restore soft deleted page posts
     *
     * @return boolean
     */
    public function restorePosts(): bool
    {
        $errors = 0;
        $posts = $this->posts()->get();
        foreach ($posts as $post) {
            $result = $post->restore();
            $errors += ($result !== true) ? 1 : 0; 
        }
      
        return ($errors == 0);
    }

    /**
     * Return true if page exist
     *
     * @param string $id Model Id, uuid or name
     * @return boolean
     */
    public function hasPage($id, ?string $exclude = null): bool
    {
        $model = $this->findPage($id,$exclude);

        return \is_object($model);
    }

    /**
     * Find page
     *
     * @param string|integer $name Model Id, Uuid or name
     * @param string|null $exclude
     * @return Model|false
     */
    public function findPage($name, ?string $exclude = null)
    {
        $model = $this->findById($name);
        if (\is_object($model) == false) {
            $model = $this->findByColumn($name,'name');
        }
        if (\is_object($model) == false) {
            $model = $this->findBySlug($name);
        }
        if (\is_object($model) == false) {
            return false;
        }

        return (empty($exclude) == false && $model->uuid == $exclude) ? false : $model;       
    } 
}
