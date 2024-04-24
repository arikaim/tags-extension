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
        $this->addApiRoute('POST','/api/admin/tags/add','TagsControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/admin/tags/update','TagsControlPanel','update','session');   
        $this->addApiRoute('DELETE','/api/admin/tags/delete/{uuid}','TagsControlPanel','delete','session');   
        $this->addApiRoute('GET','/api/admin/tags/list/[{query}]','TagsApi','getList','session');                
        // Create db tables
        $this->createDbTable('Tags');
        $this->createDbTable('TagsTranslations');
        $this->createDbTable('TagsRelations');
        // Console
        $this->registerConsoleCommand('TagsDelete');
    }   

    /**
     * Uninstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
