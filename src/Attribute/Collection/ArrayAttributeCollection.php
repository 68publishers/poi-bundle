<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection;

use ArrayIterator;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;

class ArrayAttributeCollection implements AttributeCollectionInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface[] */
	private $attributes;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface[] $attributes
	 */
	public function __construct(array $attributes)
	{
		$attributes = (static function (AttributeInterface ...$attributes) {
			return $attributes;
		})(...$attributes);

		$this->attributes = array_combine(array_map(static function (AttributeInterface $attribute) {
			return $attribute->getName();
		}, $attributes), $attributes);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasAttribute(string $name): bool
	{
		return isset($this->attributes[$name]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAttribute(string $name): AttributeInterface
	{
		if (!$this->hasAttribute($name)) {
			throw new InvalidArgumentException(sprintf(
				'Missing attribute %s.',
				$name
			));
		}

		return $this->attributes[$name];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->attributes);
	}
}
