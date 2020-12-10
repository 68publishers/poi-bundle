<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection;

interface AttributeCollectionFactoryInterface
{
	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface
	 */
	public function create(): AttributeCollectionInterface;
}
