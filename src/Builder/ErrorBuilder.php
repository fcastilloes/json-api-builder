<?php

namespace FCastillo\JsonApiBuilder\Builder;

/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:00
 */
class ErrorBuilder implements ErrorBuilderInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var ErrorSourceBuilder
     */
    protected $source;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ErrorBuilder
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ErrorBuilder
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ErrorBuilder
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ErrorBuilder
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     * @return ErrorBuilder
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return ErrorSourceBuilder
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param ErrorSourceBuilder $source
     * @return ErrorBuilder
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return object
     */
    public function getErrorObject()
    {
        $object = array_filter(
            get_object_vars($this),
            function ($value) {
                return !is_null($value) && !is_object($value);
            }
        );

        if ($this->getSource()) {
            $object['source'] = $this->getSource()->getErrorSourceObject();
        }

        return (object) $object;
    }
}
