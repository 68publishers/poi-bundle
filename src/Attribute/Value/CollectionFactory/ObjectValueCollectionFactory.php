<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ObjectValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class ObjectValueCollectionFactory implements CollectionFactoryInterface
{
	private CollectionFactoryInterface $collectionFactory;

	private string $objectValueCollectionClassName;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionFactory\CollectionFactoryInterface $collectionFactory
	 * @param string                                                                                       $objectValueCollectionClassName
	 */
	public function __construct(CollectionFactoryInterface $collectionFactory, string $objectValueCollectionClassName = ObjectValueCollection::class)
	{
		$this->collectionFactory = $collectionFactory;
		$this->objectValueCollectionClassName = $objectValueCollectionClassName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(): ValueCollectionInterface
	{
		return new $this->objectValueCollectionClassName($this->collectionFactory->create());
	}
}
