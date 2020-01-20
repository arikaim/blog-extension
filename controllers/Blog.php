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
use Arikaim\Core\Paginator\Paginator;

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
        $slug = $data['slug'];
        $page = $data->get('page',1);
        $perPage = 7;

        $categoryTranslation = Model::CategoryTranslations('category',function($model) use($data) {                
            return $model->findBySlug($data['slug']);  
        });
    
        $posts = Model::Posts('blog')->getActive();
        $posts = Model::Category('category',function($model) use($slug,$posts) {                
            return $model->relationsQuery($posts,$slug);           
        });

        $data['category'] = $categoryTranslation->category;
        $data['categoryTitle'] = Arrays::toString($categoryTranslation->category->getTitle());
        $data['paginator'] = Paginator::create($posts,$page,$perPage);
        $data['page_url'] = "/blog/category/" . $slug;

        $this->get('page')->head()
            ->param('category',$data['categoryTitle'])          
            ->ogUrl($this->getUrl($request))                   
            ->twitterCard('website')
            ->twitterSite($this->getUrl($request));       
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
        $currentPage = $data->get('page',1);
        $perPage = 7;

        $pages = Model::Pages('blog');
        $posts = Model::Posts('blog')->getActive();

        if (empty($slug) == false) {
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
        }
        if (is_object($page) == true) {
            $posts = $posts->where('page_id','=',$page->id);
            $data['page_title'] = $page->name;
            $data['page_url'] = "/blog/" . $slug;
        } 
        
        $data['paginator'] = Paginator::create($posts,$currentPage,$perPage);       
    }   

    /**
     * Show pages
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function showBlogPostPage($request, $response, $data) 
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
    } 
}
