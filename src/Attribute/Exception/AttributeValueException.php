<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Exception;

use Throwable;
use SixtyEightPublishers\PoiBundle\Exception\ExceptionInterface;

final class AttributeValueException extends \Exception implements ExceptionInterface
{
	public const CODE_MISSING_VALUE = 1001;
	public const CODE_VALIDATION_ERROR = 1002;

	/**
	 * @param string          $name
	 * @param \Throwable|NULL $previous
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public static function missingValue(string $name, ?Throwable $previous = NULL): self
	{
		return new static(sprintf(
			'Missing value with name %s.',
			$name
		), self::CODE_MISSING_VALUE, $previous);
	}

	/**
	 * @param string          $message
	 * @param \Throwable|NULL $previous
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public static function validationError(string $message, ?Throwable $previous = NULL): self
	{
		return new static($message, self::CODE_VALIDATION_ERROR, $previous);
	}
}
