<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

interface CollectionFactoryInterface
{
	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface
	 */
	public function create(): ValueCollectionInterface;
}
