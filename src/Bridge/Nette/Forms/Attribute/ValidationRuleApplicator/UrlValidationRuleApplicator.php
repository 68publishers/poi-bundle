<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\Form;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\UrlValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

final class UrlValidationRuleApplicator extends AbstractValidationRuleApplicator
{
	/**
	 * {@inheritDoc}
	 */
	protected function doApplyRules($control, ValidatorInterface $validator): void
	{
		$urlValidator = $validator->lookDown(UrlValidator::class);

		if (!$urlValidator instanceof UrlValidator) {
			return;
		}

		$control->addRule(Form::URL);
	}
}
