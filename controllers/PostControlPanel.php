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
use Arikaim\Core\Controllers\ApiController;

/**
 * Blog post control panel controler
*/
class PostControlPanel extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('blog::admin.messages');
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
        $this->requireControlPanelPermission();
        
        $this->onDataValid(function($data) {
            $title = $data->get('title');
            $page = Model::Pages('blog')->findById($data['page']);
            $post = Model::Posts('blog');

            $info = [
                'page_id'   => $page->id,
                'content'   => $data['content'],
                'title'     => $title
            ];
            
            $result = ($this->hasPost($title) == true) ? false : $post->create($info);

            $this->setResponse(is_object($result),function() use($result) {                                                       
                $this
                    ->message('post.add')
                    ->field('uuid',$result->uuid)
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
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $title = $data->get('title');           
            $post = Model::Posts('blog')->findById($data['uuid']);

            $info = [               
                'content'   => $data['content'],
                'title'     => $title
            ];
            
            $result = ($this->hasPost($title) == false) ? false : $post->update($info);

            $this->setResponse(is_object($result),function() use($result) {                                                       
                $this
                    ->message('post.update')
                    ->field('uuid',$result->uuid)
                    ->field('slug',$result->slug);           
            },'errors.post.update');
        });
        $data
            ->addRule('text:min=2','title')
            ->validate();           
    }

    /**
     * Seft delete post
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
        });

        $data->validate();    
    }
}
