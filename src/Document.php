<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 20/10/15
 * Time: 12:46
 */

namespace Fcastillo\JsonApiBuilder;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Resource\ResourceInterface;
use Art4\JsonApiClient\Utils\AccessTrait;
use FCastillo\JsonApiBuilder\Builder\ErrorBuilder;
use FCastillo\JsonApiBuilder\Builder\ItemBuilder;
use FCastillo\JsonApiBuilder\Builder\MetaBuilder;
use FCastillo\JsonApiBuilder\Utils\DataContainer;
use Art4\JsonApiClient\Utils\DataContainerInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

class Document
{
    use AccessTrait;

    /**
     * @var DataContainerInterface
     */
    protected $container;

    /**
     * @var FactoryManagerInterface
     */
    protected $manager;

    /**
     * @param object $object The document body
     * @param FactoryManagerInterface $manager
     */
    public function __construct($object, FactoryManagerInterface $manager)
    {
        if ( ! is_object($object) )
        {
            throw new ValidationException('Document has to be an object, "' . gettype($object) . '" given.');
        }

        if ( ! property_exists($object, 'data') and ! property_exists($object, 'meta') and ! property_exists($object, 'errors') )
        {
            throw new ValidationException('Document MUST contain at least one of the following properties: data, errors, meta');
        }

        if ( property_exists($object, 'data') and property_exists($object, 'errors') )
        {
            throw new ValidationException('The properties `data` and `errors` MUST NOT coexist in Document.');
        }

        $this->manager = $manager;

        $this->container = new DataContainer();

        if ( property_exists($object, 'data') )
        {
            $this->container->set('data', $this->parseData($object->data));
        }

        if ( property_exists($object, 'meta') )
        {
            $this->container->set('meta', $this->manager->getFactory()->make(
                'Meta',
                [$object->meta, $this->manager]
            ));
        }

        if ( property_exists($object, 'errors') )
        {
            $this->container->set('errors', $this->manager->getFactory()->make(
                'ErrorCollection',
                [$object->errors, $this->manager]
            ));
        }

        if ( property_exists($object, 'included') )
        {
            if ( ! property_exists($object, 'data') )
            {
                throw new ValidationException('If Document does not contain a `data` property, the `included` property MUST NOT be present either.');
            }

            $this->container->set('included', $this->manager->getFactory()->make(
                'Resource\Collection',
                [$object->included, $this->manager]
            ));
        }

        if ( property_exists($object, 'jsonapi') )
        {
            $this->container->set('jsonapi', $this->manager->getFactory()->make(
                'Jsonapi',
                [$object->jsonapi, $this->manager]
            ));
        }

        if ( property_exists($object, 'links') )
        {
            $this->container->set('links', $this->manager->getFactory()->make(
                'DocumentLink',
                [$object->links, $this->manager]
            ));
        }

        return $this;
    }

    /**
     * Get a value by the key of this document
     *
     * @param string $key The key of the value
     * @return mixed The value
     */
    public function get($key)
    {
        try
        {
            return $this->container->get($key);
        }
        catch (AccessException $e)
        {
            throw new AccessException('"' . $key . '" doesn\'t exist in Document.');
        }
    }

    /**
     * Parse the data value
     *
     * @throws ValidationException If $data isn't null or an object
     *
     * @param null|object $data Data value
     * @return ResourceInterface The parsed data
     */
    protected function parseData($data)
    {
        if ( $data === null )
        {
            return $this->manager->getFactory()->make(
                'Resource\NullResource',
                [$data, $this->manager]
            );
        }

        if ( is_array($data) )
        {
            return $this->manager->getFactory()->make(
                'Resource\Collection',
                [$data, $this->manager]
            );
        }

        if ( ! is_object($data) )
        {
            throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
        }

        $object_vars = get_object_vars($data);

        // the properties must be type and id
        if ( count($object_vars) === 2 )
        {
            $resource = $this->manager->getFactory()->make(
                'Resource\Identifier',
                [$data, $this->manager]
            );
        }
        // the 3 properties must be type, id and meta
        elseif ( count($object_vars) === 3 and property_exists($data, 'meta') )
        {
            $resource = $this->manager->getFactory()->make(
                'Resource\Identifier',
                [$data, $this->manager]
            );
        }
        else
        {
            $resource = $this->manager->getFactory()->make(
                'Resource\Item',
                [$data, $this->manager]
            );
        }

        return $resource;
    }

    public function addError(ErrorBuilder $error)
    {
        $this->container->remove('data');
        $this->container->remove('included');

        if ($this->has('errors')) {
            $this->get('errors')->add($error->getErrorObject());
        } else {
            $this->container->set('errors', $this->manager->getFactory()->make(
                'ErrorCollection',
                [[$error->getErrorObject()], $this->manager]
            ));
        }
    }

    private function makeResource($object)
    {
        // the properties must be type and id
        if ( count($object) === 2 )
        {
            return $this->manager->getFactory()->make(
                'Resource\Identifier',
                [$object, $this->manager]
            );
        }
        // the 3 properties must be type, id and meta
        elseif ( count($object) === 3 and property_exists($object, 'meta') )
        {
            return $this->manager->getFactory()->make(
                'Resource\Identifier',
                [$object, $this->manager]
            );
        }
        else
        {
            return $this->manager->getFactory()->make(
                'Resource\Item',
                [$object, $this->manager]
            );
        }
    }

    public function setData(ItemBuilder $item)
    {
        $this->container->remove('errors');
        $this->container->set('data', $this->makeResource($item->getObject()));
    }

    public function setDataCollection(array $items)
    {
        $this->container->remove('errors');

        $data = array_map(
            function (ItemBuilder $item) {
                return $item->getObject();
            },
            $items
        );

        $this->container->set('data', $this->manager->getFactory()->make(
            'Resource\Collection',
            [$data, $this->manager]
        ));
    }

    /**
     * Set a new Meta element
     * @param MetaBuilder $meta
     */
    public function setMeta(MetaBuilder $meta)
    {
        $this->container->set('meta', $this->manager->getFactory()->make(
            'Meta',
            [$meta->getObject(), $this->manager]
        ));
    }

    /**
     * Add a new value to the meta element and creates it if not exists
     * @param $key
     * @param $value
     */
    public function addMetaData($key, $value)
    {
        if ($this->has('meta')) {
            $this->get('meta')->set($key, $value);
        } else {
            $this->container->set('meta', $this->manager->getFactory()->make(
                'Meta',
                [(object) [$key => $value], $this->manager]
            ));
        }
    }

    /**
     * Get a string representation of the json api in its actual state
     * @return string
     */
    public function asString()
    {
        return json_encode($this->asArray(true));
    }
}
