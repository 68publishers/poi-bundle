<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class Attribute implements AttributeInterface
{
	private string $name;

	private TypeInterface $type;

	/** @var mixed|NULL */
	private $defaultValue;

	private array $extra = [];

	/**
	 * @param string $name
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface $type
	 * @param mixed|NULL $defaultValue
	 */
	public function __construct(string $name, TypeInterface $type, $defaultValue = NULL)
	{
		$this->name = $name;
		$this->type = $type;
		$this->defaultValue = $defaultValue;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface
	 */
	public function getType(): TypeInterface
	{
		return $this->type;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = [])
	{
		try {
			return $valueCollection->getValue($this->getName());
		} catch (AttributeValueException $e) {
			if ($e::CODE_MISSING_VALUE === $e->getCode()) {
				if (NULL !== $this->defaultValue) {
					return $this->defaultValue;
				}

				if ($this->getType()->isNullable()) {
					return NULL;
				}
			}

			throw $e;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void
	{
		$this->type->validate($value);
		$valueCollection->setValue($this->getName(), $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function lookDown(string $type): ?AttributeInterface
	{
		return $this instanceof $type ? $this : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setExtra(array $extra): AttributeInterface
	{
		$this->extra = $extra;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getExtra(?string $key = NULL)
	{
		if (NULL === $key) {
			return $this->extra;
		}

		return $this->extra[$key] ?? NULL;
	}
}
