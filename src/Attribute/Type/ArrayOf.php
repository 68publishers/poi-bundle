<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class ArrayOf implements TypeInterface
{
	private TypeInterface $valueType;

	private ?TypeInterface $keyType;

	private bool $nullable;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface      $valueType
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface|null $keyType
	 * @param bool                                                              $nullable
	 */
	public function __construct(TypeInterface $valueType, ?TypeInterface $keyType = NULL, bool $nullable = FALSE)
	{
		$this->valueType = $valueType;
		$this->keyType = $keyType;
		$this->nullable = $nullable;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface
	 */
	public function getValueType(): TypeInterface
	{
		return $this->valueType;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface|null
	 */
	public function getKeyType(): ?TypeInterface
	{
		return $this->keyType;
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

		$valueType = $this->valueType;
		$keyType = $this->keyType ?? new Mixed();

		foreach ($value as $k => $v) {
			try {
				$keyType->validate($k);
			} catch (AttributeValueException $e) {
				AttributeValueException::validationError(sprintf(
					'Invalid key "%s": %s',
					$k,
					$e->getMessage()
				));
			}

			try {
				$valueType->validate($v);
			} catch (AttributeValueException $e) {
				AttributeValueException::validationError(sprintf(
					'Invalid value with a key "%s": %s',
					$k,
					$e->getMessage()
				));
			}
		}
	}
}
