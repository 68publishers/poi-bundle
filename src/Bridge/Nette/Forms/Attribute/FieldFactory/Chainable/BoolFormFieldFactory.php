<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\Chainable;

use Nette\Forms\IControl;
use Nette\Forms\Controls\Checkbox;
use SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FormFieldOptions;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface;

final class BoolFormFieldFactory implements ChainableFormFieldFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canCreate(AttributeInterface $attribute): bool
	{
		$type = $attribute->getType();

		return $type instanceof Scalar && Scalar::TYPE_BOOL === $type->getType();
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$control = new Checkbox($attribute->getExtra(FormFieldOptions::LABEL) ?? $attribute->getName());

		$control->setDefaultValue($value);
		$control->setRequired(FALSE); # can be checkbox in normal form required?

		if (TRUE === $attribute->getExtra(FormFieldOptions::NO_TRANSLATOR)) {
			$control->setTranslator(NULL);
		}

		return $control;
	}
}
