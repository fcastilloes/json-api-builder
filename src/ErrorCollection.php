<?php

namespace Fcastillo\JsonApiBuilder;

use Art4\JsonApiClient\ErrorCollectionInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Utils\AccessTrait;
use FCastillo\JsonApiBuilder\Utils\DataContainer;
use Art4\JsonApiClient\Utils\DataContainerInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

/**
 * Error Collection Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorCollection implements ErrorCollectionInterface
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
     * @param $errors
     * @param FactoryManagerInterface $manager
     */
	public function __construct($errors, FactoryManagerInterface $manager)
	{
		if ( ! is_array($errors) )
		{
			throw new ValidationException('Errors for a collection has to be in an array, "' . gettype($errors) . '" given.');
		}

		if ( count($errors) === 0 )
		{
			throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		foreach ($errors as $error)
		{
			$this->container->set('', $this->manager->getFactory()->make(
				'Error',
				[$error, $this->manager]
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this collection.');
		}
	}

    public function add($error)
    {
        $this->container->set('', $this->manager->getFactory()->make(
            'Error',
            [$error, $this->manager]
        ));
    }
}
