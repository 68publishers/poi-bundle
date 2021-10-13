<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\Form;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\RegexValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

final class RegexValidationRuleApplicator extends AbstractValidationRuleApplicator
{
	/**
	 * {@inheritDoc}
	 */
	protected function doApplyRules($control, ValidatorInterface $validator): void
	{
		$regexValidator = $validator->lookDown(RegexValidator::class);

		if (!$regexValidator instanceof RegexValidator) {
			return;
		}

		$control->addRule(Form::PATTERN, NULL, $regexValidator->getRegex());
	}
}
