<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 10:34
 */

namespace FCastillo\JsonApiBuilder\Builder;

class MetaBuilder implements BuilderInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param mixed $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return (object) $this->data;
    }
}
