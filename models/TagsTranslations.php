<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Tags\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Tags\Models\Tags;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;

/**
 * Tags translations class
 */
class TagsTranslations extends Model  
{
    use 
        Uuid,      
        Find;
       
    protected $table = "tags_translations";

    protected $fillable = [
        'tag_id',
        'word',      
        'language'
    ];
   
    public $timestamps = false;

    public function tag()
    {
        return $this->hasOne(Tags::class,'id'); 
    }
}
