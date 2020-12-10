<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection;

use IteratorAggregate;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;

interface AttributeCollectionInterface extends IteratorAggregate
{
	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasAttribute(string $name): bool;

	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface
	 */
	public function getAttribute(string $name): AttributeInterface;
}
