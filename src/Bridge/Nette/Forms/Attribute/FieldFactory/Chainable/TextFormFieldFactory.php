<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\Chainable;

use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Controls\TextArea;
use Nette\Forms\Controls\TextInput;
use SixtyEightPublishers\PoiBundle\Attribute\Type\Scalar;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FormFieldOptions;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface;

final class TextFormFieldFactory implements ChainableFormFieldFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canCreate(AttributeInterface $attribute): bool
	{
		$type = $attribute->getType();

		return $type instanceof Scalar && in_array($attribute->getType(), [Scalar::TYPE_STRING, Scalar::TYPE_INT, Scalar::TYPE_FLOAT], TRUE);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		$scalar = $attribute->getType();
		$label = $attribute->getExtra(FormFieldOptions::LABEL) ?? $attribute->getName();

		assert($scalar instanceof Scalar);

		$control = TextArea::class === $attribute->getExtra(FormFieldOptions::PREFERRED_CONTROL) ? new TextArea($label) : new TextInput($label);

		$control->setDefaultValue($value);
		$control->setRequired(!$scalar->isNullable());

		switch ($scalar->getType()) {
			case Scalar::TYPE_INT:
				$control->addRule(Form::INTEGER);

				break;
			case Scalar::TYPE_FLOAT:
				$control->addRule(Form::FLOAT);

				break;
		}

		if (TRUE === $attribute->getExtra(FormFieldOptions::NO_TRANSLATOR)) {
			$control->setTranslator(NULL);
		}

		return $control;
	}
}
