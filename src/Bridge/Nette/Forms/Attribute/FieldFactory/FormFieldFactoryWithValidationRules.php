<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator\ValidationRuleApplicatorInterface;

final class FormFieldFactoryWithValidationRules implements FormFieldFactoryInterface
{
	private FormFieldFactoryInterface $formFieldFactory;

	private ValidationRuleApplicatorInterface $validationRuleApplicator;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\FormFieldFactoryInterface                     $formFieldFactory
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator\ValidationRuleApplicatorInterface $validationRuleApplicator
	 */
	public function __construct(FormFieldFactoryInterface $formFieldFactory, ValidationRuleApplicatorInterface $validationRuleApplicator)
	{
		$this->formFieldFactory = $formFieldFactory;
		$this->validationRuleApplicator = $validationRuleApplicator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$control = $this->formFieldFactory->create($attribute, $value);

		$this->validationRuleApplicator->applyRules($control, $attribute);

		return $control;
	}
}
