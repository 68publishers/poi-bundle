<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection;

use Traversable;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Factory\AttributeCollectionFactoryInterface;

final class LazyAttributeCollection implements AttributeCollectionInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Factory\AttributeCollectionFactoryInterface|NULL  */
	private $attributeCollectionFactory;

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface|NULL */
	private $innerCollection;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Factory\AttributeCollectionFactoryInterface $attributeCollectionFactory
	 */
	public function __construct(AttributeCollectionFactoryInterface $attributeCollectionFactory)
	{
		$this->attributeCollectionFactory = $attributeCollectionFactory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasAttribute(string $name): bool
	{
		return $this->getInnerCollection()->hasAttribute($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAttribute(string $name): AttributeInterface
	{
		return $this->getInnerCollection()->getAttribute($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): Traversable
	{
		return $this->getInnerCollection()->getIterator();
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface
	 */
	private function getInnerCollection(): AttributeCollectionInterface
	{
		if (NULL === $this->innerCollection) {
			$this->innerCollection = $this->attributeCollectionFactory->create();
			$this->attributeCollectionFactory = NULL;
		}

		return $this->innerCollection;
	}
}
