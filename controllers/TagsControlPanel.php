<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Tags\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ControlPanelApiController;

/**
 * Tags control panel controler
*/
class TagsControlPanel extends ControlPanelApiController
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
     *  Add new tag(s)
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {        
        $data           
            ->addRule('text:min=2','tags')           
            ->validate(true);       
   
        $tags = $data->get('tags',null);

        $model = Model::Tags('tags');                
        $createdTags = $model->add($tags);
        $result = (\is_array($createdTags) == true) ? \count($createdTags) : false; 
        
        $this->setResponse(($result > 0),function() use($result) {                                
            $this
                ->message('add')
                ->field('tags',$result);                
        },'errors.add');
    }

    /**
     * Update tag
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {        
        $data           
            ->addRule('text:min=2','tags')           
            ->validate(true);       

        $tags = $data->get('tags',null);

        $model = Model::Tags('tags')->findById($data['uuid']);  
        if ($model == null) {
            $this->error('Not valid tag id');
            return false;
        }               

        $result = $model->update([
            'word' => $tags
        ]);

        $this->setResponse((bool)$result,function() use($model) {                                
            $this
                ->message('update')
                ->field('uuid',$model->uuid);
        },'errors.update');
    }
   
    /**
     * Delete tag(s)
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data)
    { 
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::Tags('tags')->remove($uuid);

            $this->setResponse((bool)$result,function() use($uuid) {            
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
}
