<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection\Builder;

interface AttributeCollectionBuilderFactoryInterface
{
	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Collection\Builder\AttributeCollectionBuilderInterface
	 */
	public function create(): AttributeCollectionBuilderInterface;
}
