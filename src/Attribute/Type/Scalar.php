<?php

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class Scalar implements TypeInterface
{
	public const TYPE_STRING = 'string';
	public const TYPE_INT = 'int';
	public const TYPE_FLOAT = 'float';
	public const TYPE_BOOL = 'bool';

	public const TYPES = [
		self::TYPE_STRING,
		self::TYPE_INT,
		self::TYPE_FLOAT,
		self::TYPE_BOOL,
	];

	private string $type;

	private bool $nullable;

	/**
	 * @param string $type
	 * @param bool $nullable
	 */
	public function __construct(string $type, bool $nullable = FALSE)
	{
		if (!in_array($type, self::TYPES, TRUE)) {
			throw new InvalidArgumentException(sprintf(
				'Invalid scalar type "%s".',
				$type
			));
		}

		$this->type = $type;
		$this->nullable = $nullable;
	}

	/**
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar
	 */
	public static function string(bool $nullable = FALSE) : self
	{
		return new self(self::TYPE_STRING, $nullable);
	}

	/**
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar
	 */
	public static function int(bool $nullable = FALSE) : self
	{
		return new self(self::TYPE_INT, $nullable);
	}

	/**
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar
	 */
	public static function float(bool $nullable = FALSE) : self
	{
		return new self(self::TYPE_FLOAT, $nullable);
	}

	/**
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar
	 */
	public static function bool(bool $nullable = FALSE) : self
	{
		return new self(self::TYPE_BOOL, $nullable);
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
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

		if (!$this->isValid($value)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be of type %s, %s given.',
				$this->getType(),
				gettype($value)
			));
		}
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	private function isValid($value) : bool
	{
		switch ($this->getType()) {
			case self::TYPE_STRING:
				return is_string($value);
			case self::TYPE_INT:
				return is_int($value);
			case self::TYPE_FLOAT:
				return is_float($value);
			case self::TYPE_BOOL:
				return is_bool($value);
		}

		return FALSE;
	}
}
