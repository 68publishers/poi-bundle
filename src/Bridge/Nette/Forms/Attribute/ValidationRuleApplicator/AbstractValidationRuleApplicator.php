<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\ValidatableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;

abstract class AbstractValidationRuleApplicator implements ValidationRuleApplicatorInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function applyRules(IControl $control, AttributeInterface $attribute): void
	{
		$validatableAttribute = $attribute->lookDown(ValidatableAttribute::class);

		if (!$validatableAttribute instanceof ValidatableAttribute) {
			return;
		}

		$this->doApplyRules($control, $validatableAttribute->getValidator());
	}

	/**
	 * @param \Nette\Forms\IControl|\Nette\Forms\Controls\BaseControl                $control
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface $validator
	 *
	 * @return void
	 */
	abstract protected function doApplyRules($control, ValidatorInterface $validator): void;
}
