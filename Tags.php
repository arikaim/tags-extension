<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Tags;

use Arikaim\Core\Packages\Extension\Extension;

/**
 * Tags extension
*/
class Tags extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return boolean
    */
    public function install()
    {
        // Api Routes
        $result = $this->addApiRoute('GET','/api/tags/{id}','Tags','read');         
        // Control Panel
        $result = $this->addApiRoute('POST','/api/tags/admin/add','TagsControlPanel','add','session');   
        $result = $this->addApiRoute('DELETE','/api/tags/admin/delete/{uuid}','TagsControlPanel','delete','session');          
        // Create db tables
        $this->createDbTable('TagsSchema');
        $this->createDbTable('TagsTranslationsSchema');
        $this->createDbTable('TagsRelationsSchema');

        return true;
    }   

    /**
     * Uninstall extension
     *
     * @return boolean
     */
    public function unInstall()
    {
        $this->dropDbTable('TagsRelationsSchema');
        $this->dropDbTable('TagsTranslationsSchema');
        $this->dropDbTable('TagsSchema');
       
        return true;
    }
}
