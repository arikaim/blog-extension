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

/**
 * Blog pages control panel controler
*/
class PageControlPanel extends ControlPanelApiController
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
        $data
            ->addRule('text:min=2|required','name')
            ->validate(true); 

        $pageName = $data->get('name');
        $page = Model::Pages('blog');

        if ($page->hasPage($pageName) == true) {
            $this->error('errors.page.exist');
            return false;
        }
        $result = $page->create([
            'name' => $pageName
        ]);
        $this->setResponse(\is_object($result),function() use($result) {                                                       
            $this
                ->message('page.add')
                ->field('uuid',$result->page)
                ->field('slug',$result->slug);           
        },'errors.page.add');    
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
        $data
            ->addRule('text:min=2|required','name')
            ->validate(true); 

        $pageName = $data->get('name');
        $uuid = $data->get('uuid');
        $model = Model::Pages('blog')->findById($uuid);

        if ($model->hasPage($pageName,$uuid) == true) {
            $this->error('errors.page.exist');
            return false;
        }

        $page = $model->findById($uuid);
        if ($page == null) {
            $this->error('errors.page.id');
            return false;
        }

        $result = $page->update([
            'name' => $pageName
        ]);
        
        $this->setResponse(($result !== false),function() use($page) {                                                       
            $this
                ->message('page.update')
                ->field('uuid',$page->uuid)
                ->field('slug',$page->slug);           
        },'errors.page.update');
    }

    /**
     * Soft delete page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function softDeleteController($request, $response, $data)
    { 
        $data
            ->addRule('text:min=2|required','uuid')           
            ->validate(true); 
            
        $uuid = $data->get('uuid');
        $page = Model::Pages('blog')->findById($uuid);
        
        $result = false;
        if ($page != null) {
            $page->softDeletePosts();
            $result = $page->softDelete();
        } 

        $this->setResponse($result,function() use($uuid) {              
            $this
                ->message('page.delete')
                ->field('uuid',$uuid);                  
        },'errors.page.delete');            
    }

    /**
     * Restore page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function restoreController($request, $response, $data)
    { 
        $data
            ->addRule('text:min=2|required','uuid')           
            ->validate(true); 

        $uuid = $data->get('uuid');
        $page = Model::Pages('blog')->findById($uuid);
        
        $result = false;
        if ($page != null) {
            $page->restorePosts();
            $result = $page->restore();
        } 

        $this->setResponse($result,function() use($uuid) {              
            $this
                ->message('page.restore')
                ->field('uuid',$uuid);                  
        },'errors.page.restore');     
    }

    /**
     * Empty trash
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function emptyTrashController($request, $response, $data)
    { 
        $data->validate(true); 

        $page = Model::Pages('blog');
        $post = Model::Posts('blog');

        $page->clearDeleted();
        $post->clearDeleted();

        $this->setResponse(true,function()  {              
            $this
                ->message('trash.empty');                            
        },'errors.trash.empty');
    }
}
