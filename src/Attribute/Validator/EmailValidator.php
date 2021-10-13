<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use Nette\Utils\Validators;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

class EmailValidator extends AbstractValidator
{
	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (!Validators::isEmail($value)) {
			throw AttributeValueException::validationError('The value must be valid email.');
		}
	}
}
