<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\Form;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\EmailValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

final class EmailValidationRuleApplicator extends AbstractValidationRuleApplicator
{
	/**
	 * {@inheritDoc}
	 */
	protected function doApplyRules($control, ValidatorInterface $validator): void
	{
		$emailValidator = $validator->lookDown(EmailValidator::class);

		if (!$emailValidator instanceof EmailValidator) {
			return;
		}

		$control->addRule(Form::EMAIL);
	}
}
