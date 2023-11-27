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

use Arikaim\Extensions\Tags\Models\TagsTranslations;
use Arikaim\Extensions\Tags\Models\TagsRelations;
use Arikaim\Core\Utils\Text;
use Arikaim\Core\Utils\Utils;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Position;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Translations;

/**
 * Tags model
 */
class Tags extends Model  
{
    use Uuid,
        Position,
        Find,
        Translations;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * Translation ref column name
     *
     * @var string
     */
    protected $translationReference = 'tags_id';

    /**
     * Translation model class
     *
     * @var string
     */
    protected $translationModelClass = TagsTranslations::class;

    /**
     * Db column names which are translated to other languages
     *
     * @var array
     */
    protected $translatedAttributes = [ 
        'word'         
    ];

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'word',
        'uuid'  
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Get slug from word
     *
     * @return string
     */
    public function getSlug(): ?string
    {
        return Utils::slug($this->word);
    }

    /**
     * Tag relations
     *
     * @return Relation|null
     */
    public function relations()
    {
        return $this->hasMany(TagsRelations::class,'tags_id');
    }

    /**
     * Remove tag, translations and relations
     *
     * @param string|integer $id
     * @return bool
     */
    public function remove($id = null): bool
    {
        $model = (empty($id) == true) ? $this : $this->findById($id);
        if ($model == null) {
            return false;
        }    

        // delete relations
        $this->relations()->delete();
        // remove relation
        $model->removeTranslations();

        return (bool)$model->delete();      
    }

    /**
     * Return true if tag exist
     *
     * @param string $tag
     * @param integer|string|null $excludeId
     * @return boolean
     */
    public function hasTag(string $tag, $excludeId = null): bool
    {
        $model = $this->findTag($tag);
        if ($model == null) {
            return false;
        }

        if (empty($excludeId) == false) {
            return ($model->id == $excludeId || $model->uuid == $excludeId) ? false : true;
        }
        
        return true;        
    }

    /**
     * Find tag
     *
     * @param string $tag
     * @return Model|null
     */
    public function findTag(string $tag): ?object
    { 
        return $this->where('word','=',$tag)->first();             
    }

    /**
     * Create tag
     *
     * @param string $tag
     * @return Model|null
     */
    public function createTag(string $tag): ?object
    {       
        $model = $this->findTag($tag);

        if ($model == null) {               
            return $this->create(['word' => $tag]);            
        }
    
        return null;
    }

    /**
     * Add tag(s)
     *
     * @param string|array $tags    
     * @return array|false
     */
    public function add($tags)
    {
        if (empty($tags) == true) {
            return false;
        }
        $tags = Text::tokenize($tags);

        return $this->addTags($tags);
    }

    /**
     * Get tags id
     *
     * @param array $tags
     * @return array
     */
    public function getTagsId(array $tags): array
    {
        $result = [];
        foreach ($tags as $tag) {  
            $model = $this->findTag($tag);
            if ($model != null) {               
                $result[] = $model->id;                             
            }
        }

        return $result;
    }

    /**
     * Add tags
     *
     * @param array $tags    
     * @return array
     */
    public function addTags(array $tags): array
    {
        $result = [];
        foreach ($tags as $tag) {  
            $tag = \trim($tag);
            $tag = Utils::slug($tag);
            if (empty($tag) == false) {   
                $model = $this->createTag($tag);
                if ($model !== null) {
                    $result[] = $model->id;
                }                 
            }                                     
        }

        return $result;
    }
}
