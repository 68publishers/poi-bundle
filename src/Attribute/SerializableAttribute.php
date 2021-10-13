<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

final class SerializableAttribute extends AbstractAttributeDecorator implements ValueSerializerInterface
{
	private ValueSerializerInterface $serializer;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface                             $attribute
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface $serializer
	 */
	public function __construct(AttributeInterface $attribute, ValueSerializerInterface $serializer)
	{
		parent::__construct($attribute);

		$this->serializer = $serializer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize($value)
	{
		return $this->serializer->serialize($value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize($value)
	{
		return $this->serializer->deserialize($value);
	}
}
