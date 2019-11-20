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

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Slug;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Status;
use Arikaim\Core\Traits\Db\UserRelation;
use Arikaim\Core\Traits\Db\DateCreated;
use Arikaim\Core\Traits\Db\DateUpdated;
use Arikaim\Core\Traits\Db\SoftDelete;

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
    protected $table = "pages";

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

        return Page::getUrl($model->slug,$full,$withLanguagePath);
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
        if (is_object($model) == false) {
            $model = $this->findByColumn($id,'name');
        }

        return is_object($model);
    }
}
