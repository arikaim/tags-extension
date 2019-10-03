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
use Arikaim\Core\View\Template;

/**
 * Tags api controler
*/
class Tags extends ApiController
{
    /**
     * Read tag
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function readController($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $language = $data->get('language',Template::getLanguage());
            $id = $data->get('id');
            $tag = Model::Tags('tags')->findById($id);
            $translation = $tag->translation($language);
            $data = array_merge($translation->toArray(),$tag->toArray());
        });

        $data->validate();
    }
}
