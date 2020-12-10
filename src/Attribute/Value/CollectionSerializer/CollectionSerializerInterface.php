<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

interface CollectionSerializerInterface
{
	/**
	 * @param string                                                                                   $name
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface $serializer
	 *
	 * @return void
	 */
	public function addValueSerializer(string $name, ValueSerializerInterface $serializer): void;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface $valueCollection
	 *
	 * @return string
	 */
	public function serialize(ValueCollectionInterface $valueCollection): string;

	/**
	 * @param string $serialized
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface
	 */
	public function deserialize(string $serialized): ValueCollectionInterface;
}
