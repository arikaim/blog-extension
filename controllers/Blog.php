<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Blog\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\Controller;
use Arikaim\Core\Collection\Arrays;

/**
 * Blog controler
*/
class Blog extends Controller
{
    /**
     * Show category page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function showCategoryPage($request, $response, $data) 
    { 
        $model = Model::CategoryTranslations('category',function($model) use($data) {                
            return $model->findBySlug($data['slug']);  
        });

        if (is_object($model) == false) {
            return $this->pageNotFound($response,$data);
        }
        $data['category'] = $model->category;
        $data['categoryTitle'] = Arrays::toString($model->category->getTitle());

        $this->get('page')->head()
            ->param('category',$data['categoryTitle'])          
            ->ogUrl($this->getUrl($request))                   
            ->twitterCard('website')
            ->twitterSite($this->getUrl($request));       
                
        return $this->loadPage($request,$response,$data,'blog:category');
    }

    /**
     * Show pages
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function showBlogPage($request, $response, $data) 
    {       
        $slug = $data->get('slug',null);
        $pages = Model::Pages('blog');

        $page = $pages->findBySlug($slug);      
        if (is_object($page) == false) {
            return $this->pageNotFound($response,$data);
        } 

        if ($page->status != $page->ACTIVE()) {
            // page not published
            return $this->pageNotFound($response,$data);
        }

        if ($page->isDeleted() == true) {
            // page is deleted
            return $this->pageNotFound($response,$data);
        }

        $data['uuid'] = $page->uuid;
        
        return $this->loadPage($request,$response,$data,'blog:page');            
    }   

    /**
     * Show pages
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function showBlogPost($request, $response, $data) 
    {
        $slug = $data->get('slug',null);
        $postSlug = $data->get('postSlug',null);
        $pages = Model::Pages('blog');  
        $posts = Model::Posts('blog');  

        $page = $pages->findBySlug($slug);      
        if (is_object($page) == false) {
            return $this->pageNotFound($response,$data);
        } 

        $post = $posts->getPost($page->id,$postSlug);
        if (is_object($post) == false) {
            return $this->pageNotFound($response,$data);
        } 

        if ($post->status != $post->ACTIVE()) {
            // post not published
            return $this->pageNotFound($response,$data);
        }

        if ($post->isDeleted() == true) {
            // post is deleted
            return $this->pageNotFound($response,$data);
        }

        $data['uuid'] = $post->uuid;

        return $this->loadPage($request,$response,$data,'blog:post');      
    } 
}
