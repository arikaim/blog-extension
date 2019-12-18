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

/**
 * Blog pages control panel controler
*/
class PageControlPanel extends ApiController
{
    use Status;

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
        $this->setModelClass('Pages');
        $this->setExtensionName('Blog');
    }

    /**
     * Add page
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
            $pageName = $data->get('name');
            $page = Model::Pages('blog');

            $result = $page->create([
                'name' => $pageName
            ]);
            $this->setResponse(is_object($result),function() use($result) {                                                       
                $this
                    ->message('page.add')
                    ->field('uuid',$result->page)
                    ->field('slug',$result->slug);           
            },'errors.page.add');
        });
        $data
            ->addRule('text:min=2','name')
            ->validate();   
    }

    /**
     * Update page
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
            $pageName = $data->get('name');
            $uuid = $data->get('uuid');
            $page = Model::Pages('blog')->findById($uuid);

            $result = $page->update([
                'name' => $pageName
            ]);
            $result = ($result !== false);

            $this->setResponse($result,function() use($page) {                                                       
                $this
                    ->message('page.update')
                    ->field('uuid',$page->uuid)
                    ->field('slug',$page->slug);           
            },'errors.page.update');
        });
        $data
            ->addRule('text:min=2','name')
            ->validate();   
    }

    /**
     * Soft delete page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function softDelete($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {                  
            $uuid = $data->get('uuid');
            $page = Model::Pages('blog')->findById($uuid);
            
            $result = false;
            if (is_object($page) == true) {
                $page->softDeletePosts();
                $result = $page->softDelete();
            } 

            $this->setResponse($result,function() use($uuid) {              
                $this
                    ->message('page.delete')
                    ->field('uuid',$uuid);                  
            },'errors.page.delete');
        });
        $data
            ->addRule('text:min=2|required','uuid')           
            ->validate(); 

        return $this->getResponse();            
    }

    /**
     * Restore page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function restore($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {                  
            $uuid = $data->get('uuid');
            $page = Model::Pages('blog')->findById($uuid);
            
            $result = false;
            if (is_object($page) == true) {
                $page->restorePosts();
                $result = $page->restore();
            } 

            $this->setResponse($result,function() use($uuid) {              
                $this
                    ->message('page.restore')
                    ->field('uuid',$uuid);                  
            },'errors.page.restore');
        });
        $data
            ->addRule('text:min=2|required','uuid')           
            ->validate(); 

        return $this->getResponse();            
    }

    /**
     * Empty trash
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function emptyTrash($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {                  
            $page = Model::Pages('blog');
            $post = Model::Posts('blog');

            $errors = 0;
            $errors += ($page->clearDeleted() === false) ? 1 : 0;
            $errors += ($post->clearDeleted() === false) ? 1 : 0;
            $result = ($errors == 0);

            $this->setResponse($result,function()  {              
                $this
                    ->message('trash.empty');                            
            },'errors.trash.empty');
        });
        $data->validate(); 

        return $this->getResponse();       
    }
}
