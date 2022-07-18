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
use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;

/**
 * Blog post control panel controler
*/
class PostControlPanel extends ControlPanelApiController
{
    use Status,
        SoftDelete;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('blog::admin.messages');
        $this->setModelClass('Posts');
        $this->setExtensionName('Blog');
    }

    /**
     * Update meta tags
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateMetaTagsController($request, $response, $data) 
    {
        $this->onDataValid(function($data) { 
            $uuid = $data->get('uuid');   
            $metaTitle = $data->get('meta_title');
            $metaDescription = $data->get('meta_description');   
            $metaKeywords = $data->get('meta_keywords');  

            $model = Model::Posts('blog')->findById($uuid);             
            if ($model == null) {
                $this->error('errors.id');
                return;
            }
        
            $result = $model->update([
                'meta_title'       => $metaTitle,
                'meta_description' => $metaDescription,
                'meta_keywords'    => $metaKeywords
            ]);
           
            $this->setResponse(($result !== false),function() use($model) {               
                $this
                    ->message('post.metatags')
                    ->field('uuid',$model->uuid);   
            },'errors.post.metatags');
        });
        $data->validate(); 
    }

    /**
     * Add post
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) {
            $title = $data->get('title');
            $page = Model::Pages('blog')->findById($data['page']);
            $post = Model::Posts('blog');

            $info = [
                'page_id'   => $page->id,
                'content'   => $data['content'],
                'title'     => $title
            ];
            
            $result = ($post->hasPost($title,$page->id) == true) ? false : $post->create($info);

            $this->setResponse(\is_object($result),function() use($result) {                                                       
                $this
                    ->message('post.add')
                    ->field('uuid',$result->uuid)
                    ->field('page_id',$result->page_id)
                    ->field('slug',$result->slug);           
            },'errors.post.add');
        });
        $data
            ->addRule('text:min=2','title')
            ->validate();  
    }

    /**
     * Update post
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {   
        $this->onDataValid(function($data) {
            $title = $data->get('title');   
            $uuid =  $data->get('uuid');
            $post = Model::Posts('blog')->findById($uuid);
            if ($post == null) {
                $this->error('errors.post.id');
                return false;
            }
            
            if ($post->hasPost($title,$post->page_id) == true && $title != $post->title) {
                $this->error('errors.post.exist');
                return false;
            }
        
            $result = $post->update($data->toArray());              
       
            $this->setResponse(($result !== false),function() use($post) {                                                       
                $this
                    ->message('post.update')
                    ->field('uuid',$post->uuid)
                    ->field('slug',$post->slug);           
            },'errors.post.update');
        });
        $data
            ->addRule('text:min=2','title')
            ->validate();           
    }
}
