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

use Arikaim\Core\Extension\Extension;

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
        $this->addApiRoute('DELETE','/api/blog/admin/page/delete/{uuid}','PageControlPanel','softDelete','session');  
        $this->addApiRoute('PUT','/api/blog/admin/page/restore','PageControlPanel','restore','session');  
        // Control Panel Posts
        $this->addApiRoute('POST','/api/blog/admin/post/add','PostControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/update','PostControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/blog/admin/post/status','PostControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/blog/admin/post/delete/{uuid}','PostControlPanel','softDelete','session');  
        $this->addApiRoute('PUT','/api/blog/admin/post/restore','PostControlPanel','restore','session');  

        $this->addApiRoute('DELETE','/api/blog/admin/trash/empty','PageControlPanel','emptyTrash','session');  

        // Blog pages
        $this->addPageRoute('/{slug}','Blog','showBlogPage','blog:page');
        $this->addPageRoute('/','Blog','showBlogPage','blog:page');
        $this->addPageRoute('/{slug^(?!category)$}/{postSlug}','Blog','showBlogPost','blog:post');
        $this->addPageRoute('/category/{slug}','Blog','showCategoryPage','blog:category');
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
