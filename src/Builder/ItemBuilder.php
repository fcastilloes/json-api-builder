<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:52
 */

namespace FCastillo\JsonApiBuilder\Builder;

use stdClass;

class ItemBuilder implements ItemBuilderInterface, BuilderInterface
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

    public function getObject()
    {
        $object = new stdClass();
        $object->id = $this->id;
        $object->type = $this->type;
        $object->attributes = (object) $this->attributes;

        return $object;
    }

}
