<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use Nette\Utils\Validators;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class UrlValidator extends AbstractValidator
{
	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		if (!Validators::isUrl($value)) {
			throw AttributeValueException::validationError('The value must be valid url.');
		}
	}
}
