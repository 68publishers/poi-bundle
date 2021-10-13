<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

class RegexValidator extends AbstractValidator
{
	private string $regex;

	/**
	 * @param string $regex
	 */
	public function __construct(string $regex)
	{
		$this->regex = $regex;
	}

	/**
	 * @return string
	 */
	public function getRegex(): string
	{
		return $this->regex;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (FALSE === (bool) preg_match('#' . $this->regex . '#', $value)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be matched with regex %s',
				$this->regex
			));
		}
	}
}
