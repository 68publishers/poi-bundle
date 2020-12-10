<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ArrayValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

final class ArrayValueCollectionSerializer implements CollectionSerializerInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface[] */
	private $valueSerializers = [];

	/**
	 * {@inheritDoc}
	 */
	public function addValueSerializer(string $name, ValueSerializerInterface $serializer): void
	{
		$this->valueSerializers[$name] = $serializer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize(ValueCollectionInterface $valueCollection): string
	{
		$values = [];

		foreach ($valueCollection as $name => $value) {
			if (isset($this->valueSerializers[$name])) {
				$value = $this->valueSerializers[$name]->serialize($value);
			}

			$values[$name] = $value;
		}

		$flags = defined('JSON_THROW_ON_ERROR') ? JSON_THROW_ON_ERROR : 0;

		return json_encode($values, $flags);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function deserialize(string $serialized): ValueCollectionInterface
	{
		$flags = defined('JSON_THROW_ON_ERROR') ? JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING : JSON_BIGINT_AS_STRING;

		$values = json_decode($serialized, TRUE, 512, $flags);

		$valueCollection = new ArrayValueCollection();

		foreach ($values as $name => $value) {
			if (isset($this->valueSerializers[$name])) {
				$value = $this->valueSerializers[$name]->deserialize($value);
			}

			$valueCollection->setValue($name, $value);
		}

		return $valueCollection;
	}
}
