<?php

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;

final class FormFieldFactoryRegistry implements FormFieldFactoryInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface[]  */
	private array $fieldFactories;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\ChainableFormFieldFactoryInterface[] $fieldFactories
	 */
	public function __construct(array $fieldFactories)
	{
		$this->fieldFactories = (static fn (ChainableFormFieldFactoryInterface ...$fieldFactories) : array => $fieldFactories)(...$fieldFactories);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException
	 */
	public function create(AttributeInterface $attribute, $value): IControl
	{
		foreach ($this->fieldFactories as $fieldFactory) {
			if ($fieldFactory->canCreate($attribute)) {
				return $fieldFactory->create($attribute, $value);
			}
		}

		throw new InvalidArgumentException(sprintf(
			'Can\'t create form field for attribute "%s".',
			$attribute->getName()
		));
	}
}
