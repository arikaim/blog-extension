<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Blog\Models;

use Illuminate\Database\Eloquent\Model;

//use Arikaim\Core\Models\Users;
//use Arikaim\Core\Db\Model as DbModel;
//use Arikaim\Extensions\Category\Models\CategoryTranslations;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Slug;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Status;
use Arikaim\Core\Traits\Db\UserRelation;
use Arikaim\Core\Traits\Db\Translations;

/**
 * BlogPosts model class
 */
class BlogPosts extends Model  
{
    use Uuid,
        ToggleValue,        
        Find,
        Slug,
        Status,
        UserRelation,
        Translations;
      
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "category";

    /**
     * Translation column ref
     *
     * @var string
     */
    protected $translationReference = 'category_id';

    /**
     * Translatin model class
     *
     * @var string
     */
    protected $translationModelClass = CategoryTranslations::class;

    protected $fillable = [
        'position',       
        'status',
        'parent_id',
        'user_id'
    ];
   
    public $timestamps = false;
    
    /**
     * Parent category relation
     *
     * @return Model|null
     */
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    
    /**
     * Set child category status
     *
     * @param integer $id
     * @param integer $status
     * @return void
     */
    public function setChildStatus($id, $status)
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (is_object($model) == false) {
            return false;
        }

        foreach ($model as $item) {   
            $item->setStatus($status);        
            $this->setChildStatus($item->id,$status);
        }   
    }

    /**
     * Delete category 
     *
     * @param integer $id
     * @param boolean $removeChild
     * @return void
     */
    public function remove($id, $removeChild = true)
    {
        if ($removeChild == true) {
            $this->removeChild($id);
        }
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return false;
        }
        $relations = DbModel::CategoryRelations('category');
        $relations->deleteRelations($model->id);
        $model->removeTranslations();

        return $model->delete();      
    }

    /**
     * Return true if cateogry hav child categories
     *
     * @param integer $id
     * @return boolean
     */
    public function hasChild($id = null)
    {
        $id = (empty($id) == true) ? $this->id : $id;

        $model = $this->findByColumn($this->id,'parent_id');
        if (is_object($model) == true) {
            return ($model->count() > 0) ? true : false; 
        }

        return false;
    }

    /**
     * Remove child category
     *
     * @param integer $id
     * @return boolean
     */
    public function removeChild($id)
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (is_object($model) == false) {
            return false;
        }
        foreach ($model as $item) {
            $item->remove($item->id);          
        }
      
        return true;
    }

    /**
     * Get full cateogry title
     *
     * @param integer|string $id
     * @param string|null $language
     * @param array $items
     * @return array|null
     */
    public function getTitle($id = null, $language = null, $items = [])
    {       
        $model = (empty($id) == true) ? $this : $this->findById($id);

        if (is_object($model) == false) {
            return null;
        }

        $result = $items;
        if (empty($model->parent_id) == false) {
           $result = $model->getTitle($model->parent_id,$language,$result);        
        }     
        $title = $model->getTranslationTitle($language);
        $title = (empty($title) == true) ? $model->getTranslationTitle('en') : $title;
        $result[] = $title;

        return $result;
    }

    /**
     *  Get categories list
     *
     * @param integer $parentId
     * @return Model|null
     */
    public function getList($parentId = null)
    {      
        $model = $this->where('parent_id','=',$parentId)->get();
        return (is_object($model) == true) ? $model : null;           
    }

    /**
     * Get translation title
     *
     * @param string $language
     * @param string|null $default
     * @return string|null
     */
    public function getTranslationTitle($language, $default = null)
    {
        $model = $this->translation($language);     
        if ($model == false) {
            return $default; 
        } 
        
        return (isset($model->title) == true) ? $model->title : null;
    }

    /**
     * Return true if category exist
     *
     * @param string $title
     * @param integer|null $parentId
     * @return boolean
     */
    public function hasCategory($title, $parentId = null)
    { 
        return is_object($this->findCategory($title,$parentId));
    }

    /**
     * Find category
     *
     * @param string $title
     * @param integer|null $parentId
     * @return Model|false
     */
    public function findCategory($title, $parentId = null)
    {
        $model = $this->where('parent_id','=',$parentId)->get();
        foreach ($model as $item) {
            $translation = $item->translations()->getQuery()->where('title','=',$title)->first();   
            if (is_object($translation) == true) {
                return $item;
            }  
        }
        
        return false;
    }

    /**
     * Create categories from array
     *
     * @param array $items
     * @param integer|null $parentId
     * @param string $language
     * @return array
     */
    public function createFromArray(array $items, $parentId = null, $language = 'en')
    {
        $result = [];
        foreach ($items as $key => $value) {       
            $model = $this->findTranslation('title',$value);
            if (is_object($model) == false) {                                  
                $model = $this->create(['parent_id' => $parentId]);
                $model->saveTranslation(['title' => $value], $language, null); 
            }
            $result[] = $model->id;            
        }      
        return $result;
    }
}
