<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:17
 */

namespace FCastillo\JsonApiBuilder\Builder;

abstract class Builder implements BuilderInterface
{
    public function getObject()
    {
        return (object) array_map(
            function ($item) {
                if ($item instanceof BuilderInterface) {
                    return $item->getObject();
                }
                return $item;
            },
            array_filter(
                get_object_vars($this),
                function ($value) {
                    return !is_null($value);
                }
            )
        );
    }
}
