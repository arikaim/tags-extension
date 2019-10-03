<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Tags\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Category translations table
 */
class TagsTranslationsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $table_name = "tags_translations";

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        $table->tableTranslations('tag_id','tags',function($table) {           
            $table->string('word')->nullable(false);          
            $table->unique('word');
        });       
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {               
    }
}
