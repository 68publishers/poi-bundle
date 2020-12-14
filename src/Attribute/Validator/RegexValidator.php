<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class RegexValidator
{
	/** @var string  */
	private $regex;

	/**
	 * @param string $regex
	 */
	public function __construct(string $regex)
	{
		$this->regex = $regex;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function __invoke($value): bool
	{
		if (FALSE === (bool) preg_match('#' . $this->regex . '#', $value)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be matched with regex %s',
				$this->regex
			));
		}

		return TRUE;
	}
}
