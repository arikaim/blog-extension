<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Blog;

use Arikaim\Core\Packages\Extension\Extension;
use Arikaim\Core\Arikaim;

/**
 * Blog extension
*/
class Blog extends Extension
{
    /**
     * Install extension routes, events, jobs
     *
     * @return boolean
    */
    public function install()
    {
       
   
        // Control Panel
        //$result = $this->addApiRoute('POST','/api/category/add','Category','add','session');   
       
            
        // Register events
        $this->registerEvent('blog.create','Trigger after new blog post created');
        $this->registerEvent('blog.edit','Trigger after blog post is edited');
        $this->registerEvent('blog.delete','Trigger after blog post is deleted');     
        // Create db tables
       // $this->createDbTable('CategorySchema');
      //  $this->createDbTable('CategoryDescriptionSchema');
       
     

        return true;
    }   
}
