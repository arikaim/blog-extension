<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Blog\Subscribers;

use Arikaim\Core\Events\EventSubscriber;
use Arikaim\Core\Interfaces\Events\EventSubscriberInterface;
use Arikaim\Core\Db\Model;
use Arikaim\Core\Routes\Route;

/**
 * Sitemap subscriber class
 */
class SitemapSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {       
        $this->subscribe('sitemap.pages');
    }
    
    /**
     * Subscriber code executed.
     *
     * @param EventInterface $event
     * @return void
     */
    public function execute($event)
    {     
        $params = $event->getParameters();

        if ($params['page_name'] == 'blog>blog-page') {
            return $this->getBlogPages($params);       
        }           
        if ($params['page_name'] == 'blog>blog-post') {
            return $this->getBlogPostPages($params);       
        }  
        if ($params['page_name'] == 'blog>blog-category') {
            return $this->getCategoryPages($params,100);       
        }  

        $url = Route::getRouteUrl($params['pattern']);

        return (empty($url) == false) ? [$url] : null;  
    }

    /**
     * Get category pages url
     *
     * @param array $route
     * @return array
     */
    public function getCategoryPages($route)
    {
        $pages = [];
        $category = Model::Category('category',function($model) {                
            return $model->getActive()->where('branch','=','blog')->get();           
        });
        foreach ($category as $item) {
            $slug = $item->translation()->slug;
            $url = Route::getRouteUrl($route['pattern'],['slug' => $slug]);
            $pages[] = $url;
        }     
        
        return $pages;
    }

    /**
     * Get blog post pages
     *
     * @param array $route
     * @return array
     */
    public function getBlogPostPages($route)
    {
        $pages = [];
        $posts = Model::Posts('blog')->getActive()->get();               
        
        foreach ($posts as $item) {               
            $url = Route::getRouteUrl($route['pattern'],[
                'slug'     => $item->page->slug,
                'postSlug' => $item->slug
            ]);
         
            $pages[] = $url;
        }      

        return $pages;
    }

    /**
     * Get blog pages
     *
     * @param array $route    
     * @return array
     */
    public function getBlogPages($route)
    {
        $pages = [];
        $model = Model::Pages('blog')->getActive()->get();     
        
        foreach ($model as $item) {     
            $url = Route::getRouteUrl($route['pattern'],['slug' => $item->slug]);
            $pages[] = $url;
        }      

        return $pages;
    }
}
