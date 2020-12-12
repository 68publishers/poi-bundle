<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Stack;

use Countable;
use IteratorAggregate;

interface StackProviderInterface extends IteratorAggregate, Countable
{
	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException
	 */
	public function getStack(string $name): StackInterface;
}
