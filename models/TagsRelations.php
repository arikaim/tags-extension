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
use Arikaim\Core\Traits\Db\PolymorphicRelations;

/**
 * Tags relations class
 */
class TagsRelations extends Model  
{
    use 
        Uuid,
        PolymorphicRelations,
        Find;
       
    protected $table = "tags_relations";

    protected $fillable = [
        'tags_id',
        'relation_id',
        'relation_type'       
    ];
   
    public $timestamps = false;

    protected $relation_model_class = Tags::class;

    protected $relation_attribute_name = 'tags_id';


    /**
     * Tag model relation
     *
     * @return void
     */
    public function tag()
    {
        return $this->belongsTo(Tags::class,'tags_id');
    }
}
