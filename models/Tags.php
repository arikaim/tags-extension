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

use Arikaim\Extensions\Tags\Models\TagsTranslations;
use Arikaim\Core\Db\Model as DbModel;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Position;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Translations;
use Arikaim\Core\Utils\Text;

/**
 * Tags model
 */
class Tags extends Model  
{
    use Uuid,
        Position,
        Find,
        Translations;

    protected $table = "tags";

    protected $translation_reference_attribute = 'tags_id';

    protected $translation_model_class = TagsTranslations::class;

    protected $fillable = [
        'position'      
    ];
   
    public $timestamps = false;
    
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
     * Return true if tag exist
     *
     * @param string $tag
     * @return boolean
     */
    public function hasTag($tag, $exclude_id = null)
    {
        $model = $this->findTag($tag);
        if (is_object($model) == false) {
            return false;
        }
        if (empty($exclude_id) == false) {
            return ($model->id == $exclude_id || $model->uuid == $exclude_id) ? false : true;
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
}
