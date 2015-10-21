<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 12:40
 */

namespace FCastillo\JsonApiBuilder\Builder;

use stdClass;

interface ErrorBuilderInterface
{
    /**
     * @return stdClass
     */
    public function getErrorObject();
}
