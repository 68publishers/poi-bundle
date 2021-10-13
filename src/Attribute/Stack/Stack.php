<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Stack;

use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface;

final class Stack implements StackInterface
{
	private string $name;

	private AttributeCollectionInterface $attributeCollection;

	private ?string $valueCollectionClassName;

	private ?CollectionSerializerInterface $collectionSerializer;

	/**
	 * @param string                                                                                                  $name
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface                       $attributeCollection
	 * @param string|NULL                                                                                             $valueCollectionClassName
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface|NULL $collectionSerializer
	 */
	public function __construct(string $name, AttributeCollectionInterface $attributeCollection, ?string $valueCollectionClassName = NULL, ?CollectionSerializerInterface $collectionSerializer = NULL)
	{
		$this->name = $name;
		$this->attributeCollection = $attributeCollection;
		$this->valueCollectionClassName = $valueCollectionClassName;
		$this->collectionSerializer = $collectionSerializer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAttributes(): AttributeCollectionInterface
	{
		return $this->attributeCollection;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValueCollectionClassName(): ?string
	{
		return $this->valueCollectionClassName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValueCollectionSerializer(): ?CollectionSerializerInterface
	{
		return $this->collectionSerializer;
	}
}
