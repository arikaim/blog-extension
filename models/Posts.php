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

use Arikaim\Extensions\Blog\Models\Pages;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Slug;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;
use Arikaim\Core\Db\Traits\SoftDelete;
use Arikaim\Extensions\Category\Models\Traits\CategoryRelations;

/**
 * Posts model class
 */
class Posts extends Model
{
    use Uuid,     
        Find,   
        Slug,   
        Status,
        DateCreated,
        DateUpdated,
        SoftDelete,
        CategoryRelations,     
        UserRelation;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'position',       
        'status',
        'slug',
        'title',
        'page_id',
        'content',
        'date_created',
        'date_updated',
        'date_deleted',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'user_id'
    ];
    
    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [
        'uuid',           
        'date_created',      
        'slug',
        'title',  
        'date_updated',
        'key',
        'categories',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content'              
    ];

    /**
     * Include relations
     *
     * @var array
     */
    protected $with = [              
        'categories'
    ];

    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false; 

    /**
     * Content provider name
     *
     * @var string
     */
    protected $contentProviderName = 'blog.post';

    /**
     * Content provider title
     *
     * @var string
     */
    protected $contentProviderTitle = 'Blog Posts';

    /**
     * Content provider category
     *
     * @var string|null
     */
    protected $contentProviderCategory = null;

    /**
     * Supported content types
     *
     * @var array
     */
    protected $supportedContentTypes = ['blog.post'];

    /**
     * Page relation
     *
     * @return mixed
     */  
    public function page()
    {
        return $this->belongsTo(Pages::class,'page_id');
    }

    /**
     * Get page url prefix
     *
     * @return string
     */
    public static function getUrlPrefix()
    {
        return '/post/';
    }

    /**
     * Return true if post exist
     *
     * @param string|int $title
     * @param int|null
     * @return boolean
     */
    public function hasPost($title, ?int $pageId = null): bool
    {
        $model = $this->where('title','=',$title);
        if (empty($pageId) == false) {
            $model = $model->where('page_id','=',$pageId);
        }
      
        return \is_object($model->first());
    }

    /**
     * Get published posts
     *
     * @param integer $pageId
     * @return Builder
     */
    public function getPublishedPosts(int $pageId)
    {
        return $this->where('page_id','=',$pageId)->where('status','=',1);
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
     * Get post
     *
     * @param integer $pageId
     * @param string $slug
     * @return Model|false
     */
    public function getPost(int $pageId, string $slug)
    {
        $model = $this->getPublishedPosts($pageId);
        $model = $model->where('slug','=',$slug)->first();

        return (\is_object($model) == true) ? $model : false;
    }

    /**
     * Get post url
     *
     * @param string|integer|null $id
     * @return string
     */
    public function getUrl($id = null)
    {
        $model = ($id == null) ? $this : $this->findById($id);
        $page = $model->page()->first();    

        return Self::getUrlPrefix() . $page->slug . '/' . $model->slug;       
    }
}
