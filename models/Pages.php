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

use Arikaim\Core\View\Html\Page;
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
     * @return mixed
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
     * Get category page url
     *
     * @return string
     */
    public function getCategotyPageUrl()
    {
        return '/blog/category/{{slug}}';
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
     * @param string|integer|null $id
     * @param boolean $full
     * @param boolean $withLanguagePath
     * @return string
     */
    public function getUrl($id = null, $full = true, $withLanguagePath = false)
    {
        $model = ($id == null) ? $this : $this->findById($id);
       
        return Page::getUrl(Self::getUrlPrefix() . $model->slug,$full,$withLanguagePath);
    }

    /**
     * Soft delete all posts
     *
     * @return boolean
     */
    public function softDeletePosts()
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
    public function restorePosts()
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
     * @param string|integer $id Model Id, Uuid or Title
     * @return boolean
     */
    public function hasPage($id)
    {
        $model = $this->findById($id);
        if (\is_object($model) == false) {
            $model = $this->findByColumn($id,'name');
        }
        if (\is_object($model) == false) {
            $model = $this->findBySlug($id);
        }

        return \is_object($model);
    }
}
