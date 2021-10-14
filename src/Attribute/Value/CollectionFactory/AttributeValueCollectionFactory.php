<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory;

use SixtyEightPublishers\PoiBundle\Attribute\Value\AttributeValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;

final class AttributeValueCollectionFactory implements CollectionFactoryInterface
{
	private CollectionFactoryInterface $collectionFactory;

	private AttributeCollectionInterface $attributeCollection;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory\CollectionFactoryInterface $collectionFactory
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface            $attributeCollection
	 */
	public function __construct(CollectionFactoryInterface $collectionFactory, AttributeCollectionInterface $attributeCollection)
	{
		$this->collectionFactory = $collectionFactory;
		$this->attributeCollection = $attributeCollection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(): ValueCollectionInterface
	{
		return new AttributeValueCollection($this->attributeCollection, $this->collectionFactory->create());
	}
}
