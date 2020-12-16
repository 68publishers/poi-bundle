<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use Traversable;
use ArrayIterator;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
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
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function getIterator(bool $raw = FALSE): Traversable
	{
		$array = [];
		/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute */
		foreach ($this->attributeCollection as $attribute) {
			$array[$attribute->getName()] = $attribute->getValue($this->valueCollection, [
				AttributeInterface::GET_VALUE_CONTEXT_RAW => $raw,
			]);
		}

		return new ArrayIterator($array);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getState(): int
	{
		return $this->valueCollection->getState();
	}

	/**
	 * {@inheritDoc}
	 */
	public function changeState(int $state): void
	{
		$this->valueCollection->changeState($state);
	}
}
