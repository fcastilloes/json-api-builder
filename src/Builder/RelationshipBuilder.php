<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 11:46
 */

namespace FCastillo\JsonApiBuilder\Builder;

class RelationshipBuilder implements RelationshipBuilderInterface
{
    /**
     * @var ItemBuilderInterface[]
     */
    private $data = [];

    public function addData(ItemBuilderInterface $item)
    {
        $this->data[] = $item;
    }

    /**
     * @return object
     */
    public function getRelationshipObject()
    {
        return (object) [
            'data' => array_map(
                function (IdentifierBuilderInterface $item) {
                    return $item->getIdentifierObject();
                },
                $this->data
            ),
        ];
    }

}
