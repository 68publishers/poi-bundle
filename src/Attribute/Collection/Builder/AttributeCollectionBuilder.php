<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection\Builder;

use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\ArrayAttributeCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderFactoryInterface;

class AttributeCollectionBuilder implements AttributeCollectionBuilderInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderFactoryInterface  */
	private $attributeBuilderFactory;

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface[]  */
	private $attributeBuilders = [];

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
	public function add(string $name): AttributeBuilderInterface
	{
		return $this->attributeBuilders[] = $this->attributeBuilderFactory->create($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function build(): AttributeCollectionInterface
	{
		$collection = new ArrayAttributeCollection(array_map(static function (AttributeBuilderInterface $attributeBuilder): AttributeInterface {
			return $attributeBuilder->build();
		}, $this->attributeBuilders));

		$this->attributeBuilders = [];

		return $collection;
	}
}
