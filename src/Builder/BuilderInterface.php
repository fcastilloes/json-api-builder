<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:03
 */

namespace FCastillo\JsonApiBuilder\Builder;


use stdClass;

interface BuilderInterface
{
    /**
     * @return stdClass
     */
    public function getObject();
}
