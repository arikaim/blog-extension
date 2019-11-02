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
        $this->addApiRoute('POST','/api/category/add','Category','add','session');   
       
        // Create db tables
        $this->createDbTable('BlogPostsSchema');
        $this->createDbTable('BlogPostTranslationsSchema');
     
        return true;
    }   

    /**
     *  UnInstall extension
     *
     * @return boolean
     */
    public function unInstall()
    {
        return true;
    }
}
