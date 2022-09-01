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
        $this->addApiRoute('POST','/api/admin/blog/page/add','PageControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/admin/blog/page/update','PageControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/admin/blog/page/status','PageControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/admin/blog/page/delete/{uuid}','PageControlPanel','softDelete','session');  
        $this->addApiRoute('PUT','/api/admin/blog/page/restore','PageControlPanel','restore','session'); 
        $this->addApiRoute('DELETE','/api/admin/blog/trash/empty','PageControlPanel','emptyTrash','session'); 
        // Control Panel Posts
        $this->addApiRoute('POST','/api/admin/blog/post/add','PostControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/admin/blog/post/update','PostControlPanel','update','session');   
        $this->addApiRoute('PUT','/api/admin/blog/post/status','PostControlPanel','setStatus','session');   
        $this->addApiRoute('DELETE','/api/admin/blog/post/delete/{uuid}','PostControlPanel','softDelete','session');  
        $this->addApiRoute('PUT','/api/admin/blog/post/restore','PostControlPanel','restore','session');  
        $this->addApiRoute('PUT','/api/admin/blog/post/update/meta','PostControlPanel','updateMetaTags','session');  
        $this->addApiRoute('PUT','/api/admin/blog/post/update/image','PostControlPanel','updateImage','session');   
        // Blog pages
        $this->addHomePageRoute('/[{page:\d+}]','Blog','showBlog','blog>blog-page',null,'blogHomePage',false);
        $this->addPageRoute('/blog/page/{slug}[/{page:\d+}]','Blog','showBlog','blog>blog-page',null,'blogPage',false);
        $this->addPageRoute('/blog/category/{slug}[/{page:\d+}]','Blog','showCategory','blog>blog-category',null,'blogCategoryPage',false);      
        $this->addPageRoute('/post/{slug}/{postSlug}','Blog','showBlogPost','blog>blog-post',null,'blogPostPage',false);
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
