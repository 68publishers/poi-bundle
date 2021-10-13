<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class ValidatableAttribute extends AbstractAttributeDecorator
{
	private ValidatorInterface $validator;

	private bool $validateOnGet;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface $validator
	 * @param bool $validateOnGet
	 */
	public function __construct(AttributeInterface $attribute, ValidatorInterface $validator, bool $validateOnGet = FALSE)
	{
		parent::__construct($attribute);

		$this->validator = $validator;
		$this->validateOnGet = $validateOnGet;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface
	 */
	public function getValidator(): ValidatorInterface
	{
		return $this->validator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = [])
	{
		$value = parent::getValue($valueCollection, $context);

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
		parent::setValue($valueCollection, $value);
	}

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	private function validate($value): void
	{
		if (NULL === $value && $this->getType()->isNullable()) {
			return;
		}

		try {
			$this->getType()->validate($value);
			$this->validator->validate($value);
		} catch (AttributeValueException $e) {
			if ($e::CODE_VALIDATION_ERROR === $e->getCode()) {
				$e = AttributeValueException::validationError(sprintf(
					'Invalid value for attribute "%s".%s',
					$this->getName(),
					empty($e->getMessage()) ? '' : (' ' . $e->getMessage())
				), $e);
			}

			throw $e;
		}
	}
}
