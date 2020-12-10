<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use IteratorAggregate;

interface ValueCollectionInterface extends IteratorAggregate
{
	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function getValue(string $name);

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function setValue(string $name, $value): void;
}
