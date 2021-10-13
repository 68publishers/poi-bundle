<?php

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FormFieldOptions;

final class CustomFormFieldFactory implements FormFieldFactoryInterface
{
	private FormFieldFactoryInterface $formFieldFactory;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\FormFieldFactoryInterface $formFieldFactory
	 */
	public function __construct(FormFieldFactoryInterface $formFieldFactory)
	{
		$this->formFieldFactory = $formFieldFactory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$customFormFieldFactory = $attribute->getExtra(FormFieldOptions::FIELD_FACTORY);

		if ($customFormFieldFactory instanceof FormFieldFactoryInterface) {
			return $customFormFieldFactory->create($attribute, $value);
		}

		if (is_callable($customFormFieldFactory)) {
			return $customFormFieldFactory($attribute, $value);
		}

		return $this->formFieldFactory->create($attribute, $value);
	}
}
