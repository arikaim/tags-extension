<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
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
    protected $tableName = 'tags_translations';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        $table->tableTranslations('tags_id','tags',function($table) {           
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
