<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Exception;

final class AttributeValueException extends \Exception implements ExceptionInterface
{
	public const CODE_MISSING_VALUE = 1001;
	public const CODE_VALIDATION_ERROR = 1002;

	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public static function missingValue(string $name): self
	{
		return new static(sprintf(
			'Missing value with name %s.',
			$name
		), self::CODE_MISSING_VALUE);
	}

	/**
	 * @param string $message
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public static function validationError(string $message): self
	{
		return new static($message, self::CODE_VALIDATION_ERROR);
	}
}
