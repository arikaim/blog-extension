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
use Arikaim\Core\Arikaim;

/**
 * Blog pages control panel controler
*/
class PageControlPanel extends ApiController
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
                    ->message('add')
                    ->field('uuid',$result->page)
                    ->field('slug',$result->slug);           
            },'errors.add');
        });
        $data
            ->addRule('text:min=2','name')
            ->validate();   
    }

    public function update($request, $response, $data) 
    {    
        $this->requireControlPanelPermission();

       
        return $this->getResponse();
    }

    public function delete($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        
        return $this->getResponse();     
    }
}
