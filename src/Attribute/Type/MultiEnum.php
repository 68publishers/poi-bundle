<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class MultiEnum extends Enum
{
	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (NULL === $value && $this->isNullable()) {
			return;
		}

		if (!(is_array($value) && (!$value || array_keys($value) === range(0, count($value) - 1)))) {
			throw AttributeValueException::validationError('The value must be a list of values.');
		}

		foreach ($value as $v) {
			$this->doValidate($v);
		}
	}
}
