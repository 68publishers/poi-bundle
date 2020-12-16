<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use Traversable;
use IteratorAggregate;

interface ValueCollectionInterface extends IteratorAggregate
{
	public const STATE_NEW = 0;
	public const STATE_MANAGED = 1;
	public const STATE_UPDATED = 2;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function getValue(string $name);

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function setValue(string $name, $value): void;

	/**
	 * @return int
	 */
	public function getState(): int;

	/**
	 * @param int $state
	 */
	public function changeState(int $state): void;


	/**
	 * @param bool $raw
	 *
	 * @return \Traversable
	 */
	public function getIterator(bool $raw = FALSE): Traversable;
}
