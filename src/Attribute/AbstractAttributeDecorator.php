<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

abstract class AbstractAttributeDecorator implements AttributeInterface
{
	protected AttributeInterface $attribute;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 */
	public function __construct(AttributeInterface $attribute)
	{
		$this->attribute = $attribute;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->attribute->getName();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getType(): TypeInterface
	{
		return $this->attribute->getType();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = [])
	{
		return $this->attribute->getValue($valueCollection, $context);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void
	{
		$this->attribute->setValue($valueCollection, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function lookDown(string $type): ?AttributeInterface
	{
		return $this instanceof $type ? $this : $this->attribute->lookDown($type);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setExtra(array $extra): AttributeInterface
	{
		$this->attribute->setExtra($extra);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getExtra(?string $key = NULL)
	{
		return $this->attribute->getExtra($key);
	}
}
