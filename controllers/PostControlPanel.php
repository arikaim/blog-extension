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
use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;

/**
 * Blog post control panel controler
*/
class PostControlPanel extends ApiController
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
    }

    /**
     * Constructor
     */
    public function __construct($container) 
    {
        parent::__construct($container);
        $this->setModelClass('Posts');
        $this->setExtensionName('Blog');
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
            
            $result = ($post->hasPost($title,$page->id) == true) ? false : $post->create($info);

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
            
            if ($post->hasPost($title,$post->page_id) == true) {
                $result = $post->update($info);               
                $result = ($result !== false);
            } else {
                $result = false;
            }

            $this->setResponse($result,function() use($result) {                                                       
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
}
