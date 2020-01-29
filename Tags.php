<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Tags;

use Arikaim\Core\Extension\Extension;

/**
 * Tags extension
*/
class Tags extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return void
    */
    public function install()
    {  
        // Control Panel
        $this->addApiRoute('POST','/api/tags/admin/add','TagsControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/tags/admin/update','TagsControlPanel','update','session');   
        $this->addApiRoute('DELETE','/api/tags/admin/delete/{uuid}','TagsControlPanel','delete','session');   
        $this->addApiRoute('GET','/api/tags/admin/list/[{query}]','TagsControlPanel','getList','session');                
        // Create db tables
        $this->createDbTable('TagsSchema');
        $this->createDbTable('TagsTranslationsSchema');
        $this->createDbTable('TagsRelationsSchema');
        // console
        $this->registerConsoleCommand('TagsDelete');
    }   

    /**
     * Uninstall extension
     *
     * @return void
     */
    public function unInstall()
    {
        $this->dropDbTable('TagsRelationsSchema');
        $this->dropDbTable('TagsTranslationsSchema');
        $this->dropDbTable('TagsSchema');       
    }
}
