<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Exception\AttributeValueException;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class Attribute implements AttributeInterface
{
	/** @var string  */
	private $name;

	/** @var bool  */
	private $nullable;

	/** @var mixed|NULL */
	private $defaultValue;

	/** @var array  */
	private $extra = [];

	/**
	 * @param string     $name
	 * @param bool       $nullable
	 * @param mixed|NULL $defaultValue
	 */
	public function __construct(string $name, bool $nullable = FALSE, $defaultValue = NULL)
	{
		$this->name = $name;
		$this->nullable = $nullable;
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
	 * {@inheritDoc}
	 */
	public function isNullable(): bool
	{
		return $this->nullable;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection)
	{
		try {
			return $valueCollection->getValue($this->getName());
		} catch (AttributeValueException $e) {
			if ($e::CODE_MISSING_VALUE === $e->getCode()) {
				if (NULL !== $this->defaultValue) {
					return $this->defaultValue;
				}

				if ($this->isNullable()) {
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
		$valueCollection->setValue($this->getName(), $value);
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
