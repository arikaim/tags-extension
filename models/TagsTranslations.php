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

use Arikaim\Core\Utils\Utils;
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
     * @return Relation|null
     */
    public function tag()
    {
        return $this->hasOne(Tags::class,'id'); 
    }

    /**
     * Find tag by word
     *
     * @param string $word
     * @return Model|null
     */
    public function fidTag(?string $word)
    {
        if (empty($word) == true) {
            return null;
        }
        $word = \mb_strtolower($word);
        
        return $this->whereRaw('LOWER(word) = ?',[$word])->first();
    }

    /**
     * Get 'slug' attribute
     *
     * @return void
     */
    public function getSlugAttribute()
    {
        return Utils::slug($this->word);
    }
}
