<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 12:49
 */
namespace FCastillo\JsonApiBuilder\Builder;

interface ErrorSourceBuilderInterface
{
    /**
     * @return object
     */
    public function getErrorSourceObject();
}