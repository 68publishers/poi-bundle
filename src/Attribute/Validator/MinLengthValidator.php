<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use Nette\Utils\Strings;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class MinLengthValidator extends AbstractValidator
{
	private int $length;

	/**
	 * @param int $length
	 */
	public function __construct(int $length)
	{
		$this->length = $length;
	}

	/**
	 * @return int
	 */
	public function getLength(): int
	{
		return $this->length;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (Strings::length($value) < $this->getLength()) {
			throw AttributeValueException::validationError(sprintf(
				'Minimal length of the value is %d.',
				$this->length
			));
		}
	}
}
