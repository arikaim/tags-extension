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
     * Update tag
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function updateController($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
        
        $this->onDataValid(function($data) {
            $language = $data->get('language',null);
            $tags = $data->get('tags',null);

            $model = Model::Tags('tags');                       
            $result = $model->saveTranslation(['word' => $tags],$language,$data['uuid']);

            $this->setResponse($result,function() use($language,$model) {                                
                $this
                    ->message('update')
                    ->field('uuid',$model->uuid)
                    ->field('language',$language);           
            },'errors.update');
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

    /**
     * Get tag list
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function getList($request, $response, $data)
    {
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $language = $data->get('language',null);
            $search = $data->get('query','');
            $size = $data->get('size',15);
            $query = Model::Tags('tags')->getTranslationsQuery($language);
            $model = $query->where('word','like',"%$search%")->take($size)->get();

            $this->setResponse(is_object($model),function() use($model) {     
                $items = [];
                foreach ($model as $item) {
                    $items[]= ['name' => $item['word'],'value' => $item['tags_id']];
                }
                $this                    
                    ->field('success',true)
                    ->field('results',$items);  
            },'errors.list');
        });

        $data->validate();
        return $this->getResponse(true);
    }
}
