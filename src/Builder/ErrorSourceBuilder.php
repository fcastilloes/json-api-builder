<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:16
 */

namespace FCastillo\JsonApiBuilder\Builder;

class ErrorSourceBuilder implements ErrorSourceBuilderInterface
{
    /**
     * @var string
     */
    protected $pointer;

    /**
     * @var string
     */
    protected $parameter;

    /**
     * @return string
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * @param string $pointer
     * @return ErrorSourceBuilder
     */
    public function setPointer($pointer)
    {
        $this->pointer = $pointer;
        return $this;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     * @return ErrorSourceBuilder
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @return object
     */
    public function getErrorSourceObject()
    {
        return (object) array_filter(
            get_object_vars($this),
            function ($value) {
                return !is_null($value);
            }
        );
    }
}
