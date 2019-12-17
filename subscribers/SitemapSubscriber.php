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
use Arikaim\Core\Arikaim;

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

        if ($params['page_name'] == 'blog:page') {
            return $this->getBlogPages($params);       
        }    
        if ($params['page_name'] == 'blog:post') {
            return $this->getBlogPostPages($params);       
        }  
        if ($params['page_name'] == 'blog:category') {
            return $this->getCategoryPages($params,100);       
        }  

        $url = Arikaim::routes()->getRouteUrl($params['pattern']);

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
            return $model->getActive()->get();           
        });
        foreach ($category as $item) {
            $slug = $item->translation()->slug;
            $url = Arikaim::routes()->getRouteUrl($route['pattern'],['slug' => $slug]);
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
        $games = Model::Games('arcade')->getActive()->get();               
         
        foreach ($games as $item) {          
            $url = Arikaim::routes()->getRouteUrl($route['pattern'],['slug' => $item->slug]);
            $pages[] = $url;
        }      

        return $pages;
    }

    /**
     * Get blog pages
     *
     * @param array $route
     * @param integer $maxItems
     * @return array
     */
    public function getBlogPages($route, $maxItems = null)
    {
        $pages = [];
        $tags = Model::Tags('tags',function($model) use($maxItems) {   
            $query = $model->orderBy('id');            
            return (empty($maxItems) == true) ? $query->get() : $query->take($maxItems)->get();           
        });         
               
        foreach ($tags as $item) {     
            $word = $item->translation()->word;     
            $url = Arikaim::routes()->getRouteUrl($route['pattern'],['tag' => $word]);
            $pages[] = $url;
        }      

        return $pages;
    }
}
