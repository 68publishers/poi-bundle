<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use ArrayIterator;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;

final class AttributeValueCollection implements ValueCollectionInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface  */
	private $attributeCollection;

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface  */
	private $valueCollection;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface $attributeCollection
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface          $valueCollection
	 */
	public function __construct(AttributeCollectionInterface $attributeCollection, ValueCollectionInterface $valueCollection)
	{
		$this->attributeCollection = $attributeCollection;
		$this->valueCollection = $valueCollection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(string $name)
	{
		return $this->attributeCollection->getAttribute($name)->getValue($this->valueCollection);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(string $name, $value): void
	{
		$this->attributeCollection->getAttribute($name)->setValue($this->valueCollection, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): ArrayIterator
	{
		$array = [];
		/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute */
		foreach ($this->attributeCollection as $attribute) {
			$array[$attribute->getName()] = $attribute->getValue($this->valueCollection);
		}

		return new ArrayIterator($array);
	}
}