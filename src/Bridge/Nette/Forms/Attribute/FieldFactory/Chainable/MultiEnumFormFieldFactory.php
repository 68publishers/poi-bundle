<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\Chainable;

use Nette\Forms\IControl;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use SixtyEightPublishers\PoiBundle\Attribute\Type\MultiEnum;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FormFieldOptions;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface;

final class MultiEnumFormFieldFactory implements ChainableFormFieldFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canCreate(AttributeInterface $attribute): bool
	{
		return $attribute->getType() instanceof MultiEnum;
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$enum = $attribute->getType();
		$label = $attribute->getExtra(FormFieldOptions::LABEL) ?? $attribute->getName();

		assert($enum instanceof MultiEnum);

		$control = MultiSelectBox::class === $attribute->getExtra(FormFieldOptions::PREFERRED_CONTROL) ? new MultiSelectBox($label, $enum->getValues()) : new CheckboxList($label, $enum->getValues());

		$control->setDefaultValue($value);
		$control->setRequired(!$enum->isNullable());

		if (TRUE === $attribute->getExtra(FormFieldOptions::NO_TRANSLATOR)) {
			$control->setTranslator(NULL);
		}

		return $control;
	}
}
