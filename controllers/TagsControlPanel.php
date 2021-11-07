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
        $this->onDataValid(function($data) {
            $language = $data->get('language',null);
            $tags = $data->get('tags',null);

            $model = Model::Tags('tags');                
            $createdTags = $model->add($tags,$language);
            $result = (\is_array($createdTags) == true) ? \count($createdTags) : false; 
            $this->setResponse(($result > 0),function() use($result,$language) {                                
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {        
        $this->onDataValid(function($data) {
            $language = $data->get('language',null);
            $tags = $data->get('tags',null);

            $model = Model::Tags('tags');                       
            $result = $model->saveTranslation(['word' => $tags],$language,$data['uuid']);

            $this->setResponse((bool)$result,function() use($language,$model) {                                
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
