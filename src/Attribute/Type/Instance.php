<?php

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class Instance implements TypeInterface
{
	private string $className;

	private bool $nullable;

	/**
	 * @param string $className
	 * @param bool $nullable
	 */
	public function __construct(string $className, bool $nullable = FALSE)
	{
		$this->className = $className;
		$this->nullable = $nullable;
	}

	/**
	 * @param string $className
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Instance
	 */
	public static function of(string $className, bool $nullable = FALSE) : self
	{
		return new self($className, $nullable);
	}

	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->className;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setNullable(bool $nullable): TypeInterface
	{
		$this->nullable = $nullable;

		return $this;
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
	public function validate($value): void
	{
		if (NULL === $value && $this->isNullable()) {
			return;
		}

		$className = $this->getClassName();

		if (!($value instanceof $className)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be instance of %s.',
				$className
			));
		}
	}
}
