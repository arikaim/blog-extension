<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Arikaim\Core\Db\Model AS DbModel;
use Arikaim\Extensions\Category\Models\CategoryDescription;
use Arikaim\Core\Models\Users;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\ToggleValue;
use Arikaim\Core\Traits\Db\Position;
use Arikaim\Core\Traits\Db\Tree;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Status;

class Category extends Model  
{
    use Uuid,
        ToggleValue,
        Position,
        Find,
        Status,
        Tree;
    
    protected $table = "category";

    protected $fillable = [
        'position',
        'uuid',
        'status',
        'parent_id',
        'user_id'
    ];
   
    public $timestamps = false;
    
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    
    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class);
    }

    public function remove($id, $remove_child = true)
    {
        if ($remove_child == true) {
            $this->removeChild($id);
        }
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return false;
        }    
        DbModel::CategoryDescription('category')->remove($model->id);
        return $model->delete();      
    }

    public function removeChild($id)
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id);
        if (is_object($model) == false) {
            return false;
        }
        foreach ($model->get()->toArray() as $item) {
            $this->removeChild($item['id']);
        }
        DbModel::CategoryDescription('category')->remove($id);
        return $model->delete();
    }

    // TODO
    public function getTitle($id, $language = 'en')
    {
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return null;
        }
        return $model->description($language)->title;
    }

    public function getList($parent_id = null)
    {      
        $model = $this->where('parent_id','=',$parent_id)->get();
        return (is_object($model) == true) ? $model : false;           
    }

    public function description($language = 'en')
    {
        $model = DbModel::CategoryDescription('category');
        $model = $model->where('category_id','=',$this->id)->where('language','=',$language)->first();
    
        return (is_object($model) == false) ? false : $model;
    }

    public function saveDescription($category_id, $title, $description, $language)
    {
        $category = $this->findById($category_id);
        if ($category == false) {
            return false;
        }
        $model = Model::CategoryDescription('category');
        $data = [
            'category_id' => $category->id,
            'title'       => $title,
            'description' => $description,
            'language'    => $language
        ];

        return $model->save();
    }
}
