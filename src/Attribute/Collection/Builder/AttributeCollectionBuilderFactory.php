<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection\Builder;

use SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderFactoryInterface;

final class AttributeCollectionBuilderFactory implements AttributeCollectionBuilderFactoryInterface
{
	private AttributeBuilderFactoryInterface $attributeBuilderFactory;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderFactoryInterface $attributeBuilderFactory
	 */
	public function __construct(AttributeBuilderFactoryInterface $attributeBuilderFactory)
	{
		$this->attributeBuilderFactory = $attributeBuilderFactory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(): AttributeCollectionBuilderInterface
	{
		return new AttributeCollectionBuilder($this->attributeBuilderFactory);
	}
}
