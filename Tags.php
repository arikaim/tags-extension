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
        $this->createDbTable('TagsSchema');
        $this->createDbTable('TagsTranslationsSchema');
        $this->createDbTable('TagsRelationsSchema');
        // Console
        $this->registerConsoleCommand('TagsDelete');
        $this->registerConsoleCommand('TranslateTags');
        // Jobs
        $this->addJob("TranslateTagsJob",'translateTags',true);
        // Options
        $this->createOption('tags.job.translate.language',null);
        $this->createOption('tags.job.translate.last.id',0);       
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
