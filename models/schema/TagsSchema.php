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
 * Tags db table
 */
class TagsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'tags';

   /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns    
        $table->id();      
        $table->prototype('uuid');            
        $table->position();    
        $table->string('word')->nullable(false);          
        $table->unique('word');   
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {      
        if ($this->hasColumn($word) == false) {
            $table->string('word')->nullable(false);          
            $table->unique('word');   
        }       
    }
}
