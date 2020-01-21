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
     * @return void
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
        $this->addHomePageRoute('/[{page:\d+}]','Blog','showBlog','blog>blog-page');
        $this->addPageRoute('/blog/page/{slug}[/{page:\d+}]','Blog','showBlog','blog>blog-page');
        $this->addPageRoute('/blog/category/{slug}[/{page:\d+}]','Blog','showCategory','blog>blog-category');      
        $this->addPageRoute('/post/{slug}/{postSlug}','Blog','showBlogPost','blog>blog-post');
        
        // Relation map 
        $this->addRelationMap('post','Posts');

        // Create db tables
        $this->createDbTable('PagesSchema');
        $this->createDbTable('PostsSchema');

        // Options
        $this->createOption('blog.posts.perpage',7);
    }   

    /**
     *  UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {
    }
}
