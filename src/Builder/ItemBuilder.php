<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:52
 */

namespace FCastillo\JsonApiBuilder\Builder;

use stdClass;

class ItemBuilder implements ItemBuilderInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var RelationshipBuilderInterface[]
     */
    private $relationships = [];

    /**
     * ItemBuilder constructor.
     * @param string $id
     * @param string $type
     * @param array $attributes
     */
    public function __construct($id, $type, array $attributes = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->attributes = $attributes;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function addRelationship($type, RelationshipBuilderInterface $relationship)
    {
        $this->relationships[$type] = $relationship;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return object
     */
    public function getIdentifierObject()
    {
        $object = new stdClass();
        $object->id = $this->id;
        $object->type = $this->type;

        return $object;
    }

    /**
     * @return object
     */
    public function getItemObject()
    {
        $object = $this->getIdentifierObject();
        $object->attributes = (object) $this->attributes;

        if ($this->relationships) {
            $types = array_keys($this->relationships);
            $object->relationships = (object) array_combine(
                $types,
                array_map(
                    function (RelationshipBuilderInterface $relationship) {
                        return $relationship->getRelationshipObject();
                    },
                    $this->relationships
                )
            );
        }

        return $object;
    }

}
