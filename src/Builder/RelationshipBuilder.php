<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 11:46
 */

namespace FCastillo\JsonApiBuilder\Builder;

use stdClass;

class RelationshipBuilder implements BuilderInterface
{
    /**
     * @var ItemBuilderInterface[]
     */
    private $data = [];

    public function addData(ItemBuilderInterface $item)
    {
        $this->data[] = $item;
    }

    public function getObject()
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
