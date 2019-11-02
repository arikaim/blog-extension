<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
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
class BlogPageControlPanel extends ApiController
{
    public function add($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
 
       
        return $this->getResponse();   
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
