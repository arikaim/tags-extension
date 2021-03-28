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
     * word attribute
     *
     * @return string|null
     */
    public function getWordAttribute()
    {
        $model = $this->translation();

        return ($model !== false) ? $model->word : null;
    }

    /**
     * Get slug from english translation
     *
     * @param string|int|null $id
     * @param string|null $language
     * @return string
     */
    public function getSlug($id = null, ?string $language = null): ?string
    {
        $language = $language ?? $this->getCurrentLanguage();

        $model = (empty($id) == false) ? $this->findByid($id) : $this;
        $translation = $model->translation($language);
        $translation = (\is_object($translation) === false) ? $model->translation('en') : $translation; 

        return Utils::slug($translation->word);
    }

    /**
     * Remove tag, translations and relations
     *
     * @param string|integer $id
     * @return bool
     */
    public function remove($id): bool
    {
        $model = $this->findById($id);
        if (empty($model) == true) {
            return false;
        }    

        $relations = DbModel::TagsRelations('tags');
        $relations->deleteRelations($model->id);

        $model->removeTranslations();

        return (bool)$model->delete();      
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
     * @param integer|string|null $excludeId
     * @return boolean
     */
    public function hasTag(string $tag, $excludeId = null): bool
    {
        $model = $this->findTag($tag);
        if (empty($model) == true) {
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
    public function findTag(string $tag)
    { 
        $query = $this->whereHas('translations',function ($query) use($tag) {
            $query->where('word', '=',$tag);
        });

        return $query->first();             
    }

    /**
     * Create tag
     *
     * @param string $tag
     * @param string $language
     * @return Model|false
     */
    public function createTag(string $tag, ?string $language = null)
    {       
        $language = $language ?? 'en';
        $model = $this->findTag($tag);

        if ($model == null) {               
            $model = $this->create([]);
            if (\is_object($model) == true) {
                $result = $model->saveTranslation(['word' => $tag],$language,$model->id); 
                return ($result === false) ? false : $result;    
            }
            return false;
        }
    
        return false;
    }

    /**
     * Add tag(s)
     *
     * @param string|array $tag
     * @param string|null $language
     * @return array|false
     */
    public function add($tag, ?string $language = null)
    {
        $language = $language ?? 'en';
        if (empty($tag) == true) {
            return false;
        }
        $tags = Text::tokenize($tag);

        return $this->addTags($tags,$language);
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
     * @param string|null $language
     * @return array
     */
    public function addTags(array $tags, ?string $language = null): array
    {
        $result = [];
        foreach ($tags as $tag) {  
            $tag = \trim($tag);
            $tag = Utils::slug($tag);
            if (empty($tag) == false) {   
                $model = $this->createTag($tag,$language);
                if (\is_object($model) == true) {
                    $result[] = $model->id;
                }                 
            }                                     
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
    public function getTranslationWord(?string $language = null, ?string $default = null): ?string
    {
        $language = $language ?? 'en';

        $model = $this->translation($language);     
        if (\is_object($model) == false) {
            return $default; 
        } 
        
        return $model->word ?? null;
    }
}
