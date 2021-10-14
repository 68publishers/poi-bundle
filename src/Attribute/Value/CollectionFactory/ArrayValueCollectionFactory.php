<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ArrayValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class ArrayValueCollectionFactory implements CollectionFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function create(): ValueCollectionInterface
	{
		return new ArrayValueCollection();
	}
}
