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
            $category = Model::Tags('category');
                                                
            $model = $category->create($data->toArray());

            if (is_object($model) == true) {                      
                $result = $model->saveTranslation($data->slice(['title','description']),$data['language']);                              
            } else {
                $result = false;
            }
            $this->setResponse($result,function() use($model,$data) {                                
                $this
                    ->message('add')
                    ->field('id',$model->id)
                    ->field('uuid',$model->uuid);           
            },'errors.add');
        });
        $data           
            ->addRule('text:min=2','tag')
            ->addRule('text:min=2|max=2','language')
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
            $result = Model::Category('category')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {            
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
}
