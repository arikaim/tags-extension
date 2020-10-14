<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Tags\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Tags\Models\Tags;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;

/**
 * Tags translations class
 */
class TagsTranslations extends Model  
{
    use 
        Uuid,      
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tags_translations';

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'tags_id',
        'word',      
        'language'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Tag model relation
     *
     * @return mixed
     */
    public function tag()
    {
        return $this->hasOne(Tags::class,'id'); 
    }
}
