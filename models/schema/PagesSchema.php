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
 * Pages db table
 */
class PagesSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'pages';

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
        $table->string('name')->nullable(false);       
        $table->status();
        $table->slug();
        $table->userId();
        $table->dateCreated();
        $table->dateUpdated();
        $table->dateDeleted();
        $table->metaTags();
        // index
        $table->unique('name');
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {  
        if ($this->hasColumn('meta_title') == false) {
            $table->metaTags();
        }             
    }
}
