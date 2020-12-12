<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Stack;

use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface;

interface StackInterface
{
	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface
	 */
	public function getAttributes(): AttributeCollectionInterface;

	/**
	 * @return string|NULL
	 */
	public function getValueCollectionClassName(): ?string;

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface|NULL
	 */
	public function getValueCollectionSerializer(): ?CollectionSerializerInterface;
}
