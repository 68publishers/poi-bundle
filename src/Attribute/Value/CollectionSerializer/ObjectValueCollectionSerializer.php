<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ObjectValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

final class ObjectValueCollectionSerializer implements CollectionSerializerInterface
{
	private CollectionSerializerInterface $serializer;

	private string $objectValueCollectionClassName;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface $serializer
	 * @param string                                                                                             $objectValueCollectionClassName
	 */
	public function __construct(CollectionSerializerInterface $serializer, string $objectValueCollectionClassName = ObjectValueCollection::class)
	{
		$this->serializer = $serializer;
		$this->objectValueCollectionClassName = $objectValueCollectionClassName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function addValueSerializer(string $name, ValueSerializerInterface $serializer): void
	{
		$this->serializer->addValueSerializer($name, $serializer);
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize(ValueCollectionInterface $valueCollection): string
	{
		if ($valueCollection instanceof ObjectValueCollection) {
			$valueCollection = $valueCollection->unwrap();
		}

		return $this->serializer->serialize($valueCollection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(string $serialized): ValueCollectionInterface
	{
		return new $this->objectValueCollectionClassName($this->serializer->deserialize($serialized));
	}
}
