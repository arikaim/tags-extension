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
use Arikaim\Core\Controllers\ApiController;

/**
 * Tags api controler
*/
class TagsApi extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Get tag list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getList($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $language = $this->getPageLanguage($data);
            $search = $data->get('query','');
            $size = $data->get('size',15);
            $query = Model::Tags('tags')->getTranslationsQuery($language);
            $model = $query->where('word','like','%' . $search . '%')->take($size)->get();

            $this->setResponse(\is_object($model),function() use($model) {     
                $items = [];
                foreach ($model as $item) {
                    $items[] = [
                        'name'  => $item['word'],
                        'value' => $item['tags_id']
                    ];
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
