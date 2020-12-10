<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Exception\AttributeValueException;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class ValidatableAttribute implements AttributeInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface  */
	private $attribute;

	/** @var callable  */
	private $validator;

	/** @var bool  */
	private $validateOnGet;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 * @param callable                                                     $validator
	 * @param bool                                                         $validateOnGet
	 */
	public function __construct(AttributeInterface $attribute, callable $validator, bool $validateOnGet = FALSE)
	{
		$this->attribute = $attribute;
		$this->validator = $validator;
		$this->validateOnGet = $validateOnGet;
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
	public function isNullable(): bool
	{
		return $this->attribute->isNullable();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection)
	{
		$value = $this->attribute->getValue($valueCollection);

		if ($this->validateOnGet) {
			$this->validate($value);
		}

		return $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void
	{
		$this->validate($value);
		$this->attribute->setValue($valueCollection, $value);
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

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	private function validate($value): void
	{
		if (NULL === $value && $this->isNullable()) {
			return;
		}

		$validator = $this->validator;

		try {
			if (!$validator($value)) {
				throw AttributeValueException::validationError('');
			}
		} catch (AttributeValueException $e) {
			if ($e::CODE_VALIDATION_ERROR === $e->getCode()) {
				$e = AttributeValueException::validationError(sprintf(
					'Invalid value for attribute %s.%s',
					$this->getName(),
					empty($e->getMessage()) ? '' : (' ' . $e->getMessage())
				));
			}

			throw $e;
		}
	}
}
