<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Core\Arikaim;
use Arikaim\Core\View\Template;

/**
 * Blog control panel controler
*/
class BlogControlPanel extends ApiController
{
    /**
     * Add blog
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function add($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
 
       
        return $this->getResponse();   
    }

    /**
     * Update blog
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function update($request, $response, $data) 
    {    
        $this->requireControlPanelPermission();

       
        return $this->getResponse();
    }

    /**
     * Delete blog
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function delete($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        
        return $this->getResponse();     
    }
    
    /**
     * Enable/Disable blog
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function setStatus($request, $response, $data)
    {
        $this->requireControlPanelPermission();
 
        return $this->getResponse();   
    }

    /**
     * Read blog
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function read($request, $response, $data)
    {
        $this->requireControlPanelPermission();
      
        return $this->getResponse();  
    }
}
