<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
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
        // Control Panel Pages       
        $this->addApiRoute('POST','/api/blog/admin/page/add','PageControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/blog/admin/page/update','PageControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/blog/admin/page/status','PageControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/blog/admin/page/delete','PageControlPanel','delete','session');  
        // Control Panel Posts
        $this->addApiRoute('POST','/api/blog/admin/post/add','PostControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/update','PostControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/status','PostControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/blog/admin/post/delete','PostControlPanel','delete','session');  
        // Blog pages
        
        // Create db tables
        $this->createDbTable('PagesSchema');
        $this->createDbTable('PostsSchema');
      
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
