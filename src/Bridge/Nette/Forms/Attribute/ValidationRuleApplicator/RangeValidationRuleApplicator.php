<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\Form;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\RangeValidator;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

final class RangeValidationRuleApplicator extends AbstractValidationRuleApplicator
{
	/**
	 * {@inheritDoc}
	 */
	protected function doApplyRules($control, ValidatorInterface $validator): void
	{
		$rangeValidator = $validator->lookDown(RangeValidator::class);

		if (!$rangeValidator instanceof RangeValidator) {
			return;
		}

		$from = $rangeValidator->getFrom();
		$to = $rangeValidator->getTo();

		if (NULL !== $from && NULL === $to) {
			$control->addRule(Form::MIN, NULL, $from);

			return;
		}

		if (NULL === $from && NULL !== $to) {
			$control->addRule(Form::MAX, NULL, $to);

			return;
		}

		if (NULL !== $from && NULL !== $to) {
			$control->addRule(Form::RANGE, NULL, [$from, $to]);
		}
	}
}
