<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use ArrayIterator;
use SixtyEightPublishers\PoiBundle\Exception\AttributeValueException;

final class ArrayValueCollection implements ValueCollectionInterface
{
	/** @var array  */
	private $values;

	/**
	 * @param array $values
	 */
	public function __construct(array $values = [])
	{
		$this->values = $values;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(string $name)
	{
		if (!array_key_exists($name, $this->values)) {
			throw AttributeValueException::missingValue($name);
		}

		return $this->values[$name];
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(string $name, $value): void
	{
		$this->values[$name] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->values);
	}
}
