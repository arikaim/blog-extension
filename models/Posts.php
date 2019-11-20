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

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Slug;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Status;
use Arikaim\Core\Traits\Db\UserRelation;
use Arikaim\Core\Traits\Db\DateCreated;
use Arikaim\Core\Traits\Db\DateUpdated;
use Arikaim\Core\Traits\Db\SoftDelete;

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
        UserRelation;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "posts";

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
   
    public $timestamps = false; 

    /**
     * Return true if post exist
     *
     * @param string|integer $id Model Id, Uuid or Title
     * @return boolean
     */
    public function hasPost($id)
    {
        $model = $this->findById($id);
        if (is_object($model) == false) {
            $model = $this->findByColumn($id,'title');
        }

        return is_object($model);
    }
}
