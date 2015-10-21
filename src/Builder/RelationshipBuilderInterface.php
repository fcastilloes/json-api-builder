<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 17:26
 */
namespace FCastillo\JsonApiBuilder\Builder;

interface RelationshipBuilderInterface
{
    /**
     * @return object
     */
    public function getRelationshipObject();
}