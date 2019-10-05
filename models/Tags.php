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

    public function hasTag($tag)
    {
        return is_object($this->findTag($tag));
    }

    public function findTag($tag)
    { 
        $translations = DbModel::TagsTranslations('tags');      
        $translation = $translations->where('word','=',$tag)->first();

        return (is_object($translation) == false) ? null : $this->findByid($translation->tag_id);                           
    }

    public function createTag($tag, $language = null)
    {
        echo "create $tag";
        if ($this->hasTag($tag) == false) {
            echo "create";
            $model = $this->create();
            echo "ID:" . $model->id;

            //var_dump($model);

            return  $model->saveTranslation(['word' => $tag],$language,$model->id);
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
    public function add($tag, $language = null)
    {
        if (empty($tag) == true) {
            return false;
        }
        $words = Text::tokenize($tag);

        return $this->addTags($words,$language);
    }

    public function addTags(array $tags, $language = null)
    {
        $result = [];
        foreach ($tags as $tag) {                    
            $model = $this->createTag($tag,$language);
            if (is_object($model) == true) {       
                $result[] = $model->id;                     
            }                  
        }

        return $result;
    }
}
