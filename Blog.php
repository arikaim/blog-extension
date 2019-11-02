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
        // Pages
        $this->addApiRoute('POST','/api/blog/admin/page/add','BlogPageControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/blog/admin/page/update','BlogPageControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/blog/admin/page/status','BlogPageControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/blog/admin/page/delete','BlogPageControlPanel','delete','session');  
        // Posts
        $this->addApiRoute('POST','/api/blog/admin/post/add','BlogPostControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/update','BlogPostControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/status','BlogPostControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/blog/admin/post/delete','BlogPostControlPanel','delete','session');  
        
        // Blog pages
        

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
