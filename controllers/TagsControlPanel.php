<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Tags\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Core\Arikaim;

/**
 * Tags control panel controler
*/
class TagsControlPanel extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('tags::admin.messages');
    }

    /**
     * Add new tag(s)
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function addController($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
        
        $this->onDataValid(function($data) {
            $language = $data->get('language',null);
            $tags = $data->get('tags',null);

            $model = Model::Tags('tags');                
            $result = $model->add($tags,$language);
 
            $this->setResponse($result,function() use($result,$language) {                                
                $this
                    ->message('add')
                    ->field('tags',$result)
                    ->field('language',$language);           
            },'errors.add');
        });
        $data           
            ->addRule('text:min=2','tags')           
            ->validate();       
    }

    /**
     * Delete tag(s)
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function deleteController($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::Tags('tags')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {            
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
}
