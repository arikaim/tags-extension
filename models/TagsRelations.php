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

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\PolymorphicRelations;

/**
 * Tags relations class
 */
class TagsRelations extends Model  
{
    use 
        Uuid,
        PolymorphicRelations,
        Find;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "tags_relations";

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'tags_id',
        'relation_id',
        'relation_type'       
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Relation model class
     *
     * @var string
     */
    protected $relationModelClass = Tags::class;

    /**
     * Relation column name
     *
     * @var string
     */
    protected $relationColumnName = 'tags_id';

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
