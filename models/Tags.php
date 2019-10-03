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

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Position;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Translations;

class Tags extends Model  
{
    use Uuid,
        Position,
        Find,
        Translations;

    protected $table = "tags";

    protected $translation_reference_attribute = 'tag_id';

    protected $translation_model_class = TagsTranslations::class;

    protected $fillable = [
        'position'      
    ];
   
    public $timestamps = false;
    

    public function remove($id)
    {
       
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return false;
        }    
        $model->removeTranslations();

        return $model->delete();      
    }

    public function getTranslationTitle($language, $default = null)
    {
        $model = $this->translation($language);     
        if ($model == false) {
            return $default; 
        } 
        
        return (isset($model->title) == true) ? $model->title : null;
    }

    public function hasCategory($title, $parent_id = null)
    { 
        return is_object($this->findCategory($title,$parent_id));
    }

    

    public function createFromArray(array $items, $parent_id = null, $language = 'en')
    {
        $result = [];
        foreach ($items as $key => $value) {                    
            $model = $this->findCategory($value,$parent_id);

            if (is_object($model) == false) {       
               
                $model = $this->create(['parent_id' => $parent_id]);
                $model->saveTranslation(['title' => $value], $language, null); 
            }
            $result[] = $model->id;            
        }
        return $result;
    }
}
