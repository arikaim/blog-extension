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
    public function updateMetaTags($request, $response, $data) 
    {
        $data->validate(true); 

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
    }

    /**
     * Add post
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function add($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','title')
            ->validate(true); 

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
    }

    /**
     * Update post
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function update($request, $response, $data) 
    {   
        $data
            ->addRule('text:min=2|required','title')
            ->validate(true);       
            
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
    }

    /**
     * Update image
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateImage($request, $response, $data) 
    {   
        $data           
            ->validate(true);       
            
        $imageId = $data->get('image_id');   
        $uuid =  $data->get('uuid');
        $post = Model::Posts('blog')->findById($uuid);
        if ($post == null) {
            $this->error('errors.post.id');
            return false;
        }
        
        $result = $post->update([
            'image_id' => $imageId
        ]);              
    
        $this->setResponse(($result !== false),function() use($post) {                                                       
            $this
                ->message('post.update')
                ->field('uuid',$post->uuid)
                ->field('slug',$post->slug);           
        },'errors.post.update');
    }

    /**
     * Update summary
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateSummaryController($request, $response, $data) 
    {   
        $data           
            ->validate(true);       

        $uuid = $data->get('uuid');
        $summary = $data->get('summary');   
       
        $post = Model::Posts('blog')->findById($uuid);
        if ($post == null) {
            $this->error('errors.post.id');
            return false;
        }
        
        $result = $post->update([
            'summary' => $summary
        ]);              
    
        $this->setResponse(($result !== false),function() use($post) {                                                       
            $this
                ->message('post.update')
                ->field('uuid',$post->uuid);                    
        },'errors.post.update');
    }
}
