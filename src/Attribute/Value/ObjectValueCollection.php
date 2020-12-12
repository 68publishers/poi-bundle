<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value;

use Traversable;

class ObjectValueCollection implements ValueCollectionInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Value\ArrayValueCollection|\SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface  */
	private $inner;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface|NULL $inner
	 */
	public function __construct(?ValueCollectionInterface $inner = NULL)
	{
		$this->inner = $inner ?? new ArrayValueCollection();
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function __set(string $name, $value): void
	{
		$this->setValue($name, $value);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function __get(string $name)
	{
		return $this->getValue($name);
	}

	/**
	 * @internal
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface
	 */
	public function unwrap(): ValueCollectionInterface
	{
		return $this->inner;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): Traversable
	{
		return $this->inner->getIterator();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(string $name)
	{
		return $this->inner->getValue($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(string $name, $value): void
	{
		$this->inner->setValue($name, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getState(): int
	{
		return $this->inner->getState();
	}

	/**
	 * {@inheritDoc}
	 */
	public function changeState(int $state): void
	{
		$this->inner->changeState($state);
	}
}
