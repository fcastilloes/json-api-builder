<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 15:51
 */

namespace FCastillo\JsonApiBuilder\Builder;


interface ItemBuilderInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getAttributes();
}
