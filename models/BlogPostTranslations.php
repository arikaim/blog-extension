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

use Arikaim\Extensions\Blog\Models\BlogPosts;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Slug;

class BlogPostTranslations extends Model  
{
    use 
        Uuid,
        Slug,
        Find;
       
    protected $table = "category_translations";

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'language'
    ];
   
    public $timestamps = false;

    /**
     * Category relation
     *
     * @return mixed
     */
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id'); 
    }
}
