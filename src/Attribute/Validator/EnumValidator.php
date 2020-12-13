<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class EnumValidator
{
	/** @var array  */
	private $enum;

	/**
	 * @param array $enum
	 */
	public function __construct(array $enum)
	{
		$this->enum = $enum;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function __invoke($value): bool
	{
		if (!in_array($value, $this->enum, TRUE)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be one of these: [%s]',
				implode(', ', $this->enum)
			));
		}

		return TRUE;
	}
}
