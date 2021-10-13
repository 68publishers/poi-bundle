<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\Chainable;

use Nette\Forms\IControl;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use SixtyEightPublishers\PoiBundle\Attribute\Type\Enum;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FormFieldOptions;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface;

final class EnumFormFieldFactory implements ChainableFormFieldFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canCreate(AttributeInterface $attribute): bool
	{
		return Enum::class === get_class($attribute->getType());
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$enum = $attribute->getType();
		$label = $attribute->getExtra(FormFieldOptions::LABEL) ?? $attribute->getName();

		assert($enum instanceof Enum);

		$control = RadioList::class === $attribute->getExtra(FormFieldOptions::PREFERRED_CONTROL) ? new RadioList($label, $enum->getValues()) : new SelectBox($label, $enum->getValues());

		$control->setDefaultValue($value);
		$control->setRequired(!$enum->isNullable());

		if (TRUE === $attribute->getExtra(FormFieldOptions::NO_TRANSLATOR)) {
			$control->setTranslator(NULL);
		}

		return $control;
	}
}
