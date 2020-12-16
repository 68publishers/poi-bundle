<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class ValidatableAttribute extends AbstractAttributeDecorator
{
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
		parent::__construct($attribute);

		$this->validator = $validator;
		$this->validateOnGet = $validateOnGet;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = [])
	{
		$value = parent::getValue($valueCollection);

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
