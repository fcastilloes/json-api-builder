<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 21/10/15
 * Time: 10:43
 */

namespace Fcastillo\JsonApiBuilder;


use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\MetaInterface;
use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainerInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use FCastillo\JsonApiBuilder\Utils\DataContainer;

class Meta implements MetaInterface
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
     * @param object $object The error object
     *
     * @param FactoryManagerInterface $manager
     */
    public function __construct($object, FactoryManagerInterface $manager)
    {
        if ( ! is_object($object) )
        {
            throw new ValidationException('Meta has to be an object, "' . gettype($object) . '" given.');
        }

        $this->manager = $manager;

        $this->container = new DataContainer();

        $object_vars = get_object_vars($object);

        if ( count($object_vars) === 0 )
        {
            return $this;
        }

        foreach ($object_vars as $name => $value)
        {
            $this->container->set($name, $value);
        }

        return $this;
    }

    /**
     * Get a value by the key of this object
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }

    /**
     * Set a value to this object
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->container->set($key, $value);
    }
}
