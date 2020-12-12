<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Stack;

use ArrayIterator;
use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;

final class StackProvider implements StackProviderInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface[] */
	private $stacks;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface[] $stacks
	 */
	public function __construct(array $stacks = [])
	{
		$stacks = (static function (StackInterface ...$stacks) {
			return $stacks;
		})(...$stacks);

		$this->stacks = array_combine(array_map(static function (StackInterface $stack) {
			return $stack->getName();
		}, $stacks), $stacks);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator(array_values($this->stacks));
	}

	/**
	 * {@inheritDoc}
	 */
	public function count(): int
	{
		return count($this->stacks);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getStack(string $name): StackInterface
	{
		if (!isset($this->stacks[$name])) {
			throw new InvalidArgumentException(sprintf(
				'Stack with name "%s" is not defined.',
				$name
			));
		}

		return $this->stacks[$name];
	}
}
