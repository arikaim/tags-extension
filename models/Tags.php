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
use Arikaim\Core\Db\Model as DbModel;
use Arikaim\Core\Utils\Text;

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
    protected $table = "tags";

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
    ];

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'position'      
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * Get slug from english translation
     *
     * @return string
     */
    public function getSlug($id = null)
    {
        $model = (empty($id) == false) ? $this->findByid($id) : $this;
        $translateion = $model->translation('en');

        return (is_object($translateion) == true) ? $translateion->word : null; 
    }

    /**
     * Remove tag, translations and relations
     *
     * @param string|integer $id
     * @return bool
     */
    public function remove($id)
    {
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return false;
        }    

        $relations = DbModel::TagsRelations('tags');
        $relations->deleteRelations($model->id);

        $model->removeTranslations();

        return $model->delete();      
    }

    /**
     * Title attribute
     *
     * @return string|null
     */
    public function getTitleAttribute()
    {
        return $this->getTranslationWord();        
    }

    /**
     * Return true if tag exist
     *
     * @param string $tag
     * @param integer $excludeId
     * @return boolean
     */
    public function hasTag($tag, $excludeId = null)
    {
        $model = $this->findTag($tag);
        if (is_object($model) == false) {
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
     * @return Model
     */
    public function findTag($tag)
    { 
        return $this->findTranslation('word',$tag);                       
    }

    /**
     * Create tag
     *
     * @param string $tag
     * @param string $language
     * @return Model
     */
    public function createTag($tag, $language = null)
    {       
        $language = (empty($language) == true) ? 'en' : $language;
        
        if (empty($tag) == true) {
            return false;
        }
        $model = $this->findTag($tag);
        if (is_object($model) == false) {               
            $model = $this->create();
            $model->saveTranslation(['word' => $tag],$language,$model->id);          
        }
      
        return $model;
    }

    /**
     * Add tag(s)
     *
     * @param string|array $tag
     * @param string|null $language
     * @return array|false
     */
    public function add($tag, $language = null)
    {
        $language = (empty($language) == true) ? 'en' : $language;
        if (empty($tag) == true) {
            return false;
        }
        $tags = Text::tokenize($tag);

        return $this->addTags($tags,$language);
    }

    /**
     * Add tags
     *
     * @param array $tags
     * @param string|null $language
     * @return array
     */
    public function addTags(array $tags, $language = null)
    {
        $result = [];
        foreach ($tags as $tag) {                    
            $model = $this->createTag($tag,$language);
            $result[] = $model->id;                                     
        }

        return $result;
    }

    /**
     * Get translation title
     *
     * @param string|null $language
     * @param string|null $default
     * @return string|null
     */
    public function getTranslationWord($language = null, $default = null)
    {
        $language = (empty($language) == true) ? 'en' : $language;

        $model = $this->translation($language);     
        if (is_object($model) == false) {
            return $default; 
        } 
        
        return (isset($model->word) == true) ? $model->word : null;
    }
}
