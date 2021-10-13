<?php

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

class Enum implements TypeInterface
{
	private array $values;

	private bool $nullable;

	/**
	 * @param array $values
	 * @param bool $nullable
	 */
	public function __construct(array $values, bool $nullable = FALSE)
	{
		$this->values = $values;
		$this->nullable = $nullable;
	}

	/**
	 * @param array $values
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Enum
	 */
	public static function from(array $values, bool $nullable = FALSE) : self
	{
		return new static($values, $nullable);
	}

	/**
	 * @return array
	 */
	public function getValues(): array
	{
		return $this->values;
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

		$this->doValidate($value);
	}

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	protected function doValidate($value) : void
	{
		if (!in_array($value, $this->getValues(), TRUE)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be one of these: [%s]',
				implode(', ', $this->getValues())
			));
		}
	}
}
