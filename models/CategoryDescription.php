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
use Arikaim\Core\Traits\Db\Uuid;;
use Arikaim\Extensions\Category\Models\Category;

class CategoryDescription extends Model  
{
    use Uuid;

    protected $table = "category_description";
    
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'language'
    ];
   
    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function remove($id, $language = null)
    {
        $model = $this->where('category_id','=',$id);
        if (is_object($model) == false) {
            return false;
        }
        if ($language != null) {
            $model = $model->where('language','=',$language);
        }
        return $model->delete();
    }
}
