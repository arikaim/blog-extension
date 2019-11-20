<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Blog\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Posts db table
 */
class PostsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = "posts";

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns    
        $table->id();     
        $table->prototype('uuid');       
        $table->status();
        $table->position();
        $table->userId();
        $table->slug(false);
        $table->longText('content')->nullable(true);      
        $table->string('title')->nullable(true);
        $table->relation('page_id','pages');
        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        // index
        $table->unique(['title','page_id']);
        $table->unique(['slug','page_id']);
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {              
    }
}
