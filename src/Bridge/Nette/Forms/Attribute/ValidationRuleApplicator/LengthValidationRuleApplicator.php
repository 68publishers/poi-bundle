<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\Form;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\MaxLengthValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\MinLengthValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

final class LengthValidationRuleApplicator extends AbstractValidationRuleApplicator
{
	/**
	 * {@inheritDoc}
	 */
	protected function doApplyRules($control, ValidatorInterface $validator): void
	{
		$minLengthValidator = $validator->lookDown(MinLengthValidator::class);
		$maxLengthValidator = $validator->lookDown(MaxLengthValidator::class);

		if ($minLengthValidator instanceof MinLengthValidator) {
			$control->addRule(Form::MIN_LENGTH, NULL, $minLengthValidator->getLength());
		}

		if ($maxLengthValidator instanceof MaxLengthValidator) {
			$control->addRule(Form::MAX_LENGTH, NULL, $maxLengthValidator->getLength());
		}
	}
}
