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
     * @param string $integer Title
     * @return boolean
     */
    public function hasPost($title, $pageId = null)
    {
        $model = $this
            ->where('title','=',$title)
            ->where('page_id','=',$pageId)->first();

        return \is_object($model);
    }

    /**
     * Get published posts
     *
     * @param integer $pageId
     * @return Builder
     */
    public function getPublishedPosts($pageId)
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
     * @return Model
     */
    public function getPost($pageId, $slug)
    {
        $model = $this->getPublishedPosts($pageId);
        $model = $model->where('slug','=',$slug)->first();

        return (\is_object($model) == true) ? $model : false;
    }

    /**
     * Get post url
     *
     * @param string|integer|null $id
     * @param boolean $full
     * @param boolean $withLanguagePath
     * @return string
     */
    public function getUrl($id = null, $full = true, $withLanguagePath = false)
    {
        $model = ($id == null) ? $this : $this->findById($id);
        $page = $model->page()->first();        
        $url = Self::getUrlPrefix() . $page->slug . '/' . $model->slug;
    
        return Page::getUrl($url,$full,$withLanguagePath);
    }
}
