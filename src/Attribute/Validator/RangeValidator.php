<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class RangeValidator
{
	/** @var float|int  */
	private $from;

	/** @var float|int  */
	private $to;

	/**
	 * @param int|float $from
	 * @param int|float $to
	 */
	public function __construct($from, $to)
	{
		$this->from = $from;
		$this->to = $to;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function __invoke($value): bool
	{
		if (!is_numeric($value)) {
			throw AttributeValueException::validationError('The value must be numeric.');
		}

		if (!($value >= $this->from && $value <= $this->to)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be number between %s and %s',
				$this->from,
				$this->to
			));
		}

		return TRUE;
	}
}
