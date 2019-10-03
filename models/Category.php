<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Arikaim\Core\Models\Users;
use Arikaim\Extensions\Category\Models\CategoryTranslations;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\ToggleValue;
use Arikaim\Core\Traits\Db\Position;
use Arikaim\Core\Traits\Db\Tree;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\Status;
use Arikaim\Core\Traits\Db\Translations;

class Category extends Model  
{
    use Uuid,
        ToggleValue,
        Position,
        Find,
        Status,
        Translations,
        Tree;
    
    protected $table = "category";

    protected $translation_reference_attribute = 'category_id';

    protected $translation_model_class = CategoryTranslations::class;

    protected $fillable = [
        'position',       
        'status',
        'parent_id',
        'user_id'
    ];
   
    public $timestamps = false;
    
    /**
     * Parent category relation
     *
     * @return Model|null
     */
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    
    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function setChildStatus($id, $status)
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (is_object($model) == false) {
            return false;
        }

        foreach ($model as $item) {   
            $item->setStatus($status);        
            $this->setChildStatus($item->id,$status);
        }   
    }

    public function remove($id, $remove_child = true)
    {
        if ($remove_child == true) {
            $this->removeChild($id);
        }
        $model = $this->findById($id);
        if (is_object($model) == false) {
            return false;
        }    
        $model->removeTranslations();

        return $model->delete();      
    }

    public function hasChild($id = null)
    {
        $id = (empty($id) == true) ? $this->id : $id;

        $model = $this->findByColumn($this->id,'parent_id');
        if (is_object($model) == true) {
            return ($model->count() > 0) ? true : false; 
        }
        return false;
    }

    public function removeChild($id)
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (is_object($model) == false) {
            return false;
        }

        foreach ($model as $item) {
            $item->removeTranslations();
            $this->removeChild($item->id);
            $item->delete();
        }
      
        return true;
    }

    /**
     * Get full cateogry title
     *
     * @param integer|string $id
     * @param string|null $language
     * @param array $items
     * @return array
     */
    public function getTitle($id = null, $language = null, $items = [])
    {       
        $model = (empty($id) == true) ? $this : $this->findById($id);

        if (is_object($model) == false) {
            return null;
        }

        $result = $items;
        if (empty($model->parent_id) == false) {
           $result = $model->getTitle($model->parent_id,$language,$result);        
        }     
        $title = $model->getTranslationTitle($language);
        $title = (empty($title) == true) ? $model->getTranslationTitle('en') : $title;
        $result[] = $title;

        return $result;
    }

    public function getList($parent_id = null)
    {      
        $model = $this->where('parent_id','=',$parent_id)->get();
        return (is_object($model) == true) ? $model : null;           
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

    /**
     * Find category
     *
     * @param string $title
     * @param integer|null $parent_id
     * @return void
     */
    public function findCategory($title, $parent_id = null)
    {
        $model = $this->where('parent_id','=',$parent_id)->get();
        foreach ($model as $item) {
            $translation = $item->translations()->getQuery()->where('title','=',$title)->first();   
            if (is_object($translation) == true) {
                return $item;
            }  
        }
        
        return false;
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
