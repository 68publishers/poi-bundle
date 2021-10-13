<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class RangeValidator extends AbstractValidator
{
	/** @var float|int  */
	private $from;

	/** @var float|int  */
	private $to;

	/**
	 * @param int|float|NULL $from
	 * @param int|float|NULL $to
	 */
	public function __construct($from, $to)
	{
		if (NULL === $from && NULL === $to) {
			throw new InvalidArgumentException('Almost one of provided ranges must be not null.');
		}

		$this->from = $from;
		$this->to = $to;
	}

	/**
	 * @return float|int|NULL
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * @return float|int|NULL
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (!is_numeric($value)) {
			throw AttributeValueException::validationError('The value must be numeric.');
		}

		if (NULL !== $this->from && NULL === $this->to && $value < $this->from) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be number greater than or equal to %s.',
				$this->from,
			));
		}

		if (NULL === $this->from && NULL !== $this->to && $value > $this->to) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be number less than or equal to %s.',
				$this->to,
			));
		}

		if (!($value >= $this->from && $value <= $this->to)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be number between %s and %s',
				$this->from,
				$this->to
			));
		}
	}
}
