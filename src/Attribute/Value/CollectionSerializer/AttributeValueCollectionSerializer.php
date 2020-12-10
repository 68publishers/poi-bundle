<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer;

use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ArrayValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\AttributeValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

final class AttributeValueCollectionSerializer implements CollectionSerializerInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface  */
	private $serializer;

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface  */
	private $attributeCollection;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface $serializer
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface                  $attributeCollection
	 */
	public function __construct(CollectionSerializerInterface $serializer, AttributeCollectionInterface $attributeCollection)
	{
		$this->serializer = $serializer;
		$this->attributeCollection = $attributeCollection;

		/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute */
		foreach ($attributeCollection as $attribute) {
			$valueSerializer = $attribute->getExtra(AttributeInterface::EXTRA_KEY_SERIALIZER);

			if ($valueSerializer instanceof ValueSerializerInterface) {
				$this->addValueSerializer($attribute->getName(), $valueSerializer);
			}
		}
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
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function serialize(ValueCollectionInterface $valueCollection): string
	{
		if (!$valueCollection instanceof AttributeValueCollection) {
			$originalValueCollection = $valueCollection;
			$valueCollection = new AttributeValueCollection($this->attributeCollection, new ArrayValueCollection());

			# Validation
			foreach ($originalValueCollection as $name => $value) {
				$valueCollection->setValue($name, $value);
			}
		}

		return $this->serializer->serialize($valueCollection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize(string $serialized): ValueCollectionInterface
	{
		return new AttributeValueCollection($this->attributeCollection, $this->serializer->deserialize($serialized));
	}
}
