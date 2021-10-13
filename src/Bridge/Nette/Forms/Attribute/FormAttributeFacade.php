<?php

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute;

use Traversable;
use Nette\Forms\Container;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;
use SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\FormFieldFactoryInterface;

final class FormAttributeFacade implements FormAttributeFacadeInterface
{
	private StackProviderInterface $stackProvider;

	private FormFieldFactoryInterface $formFieldFactory;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface $stackProvider
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory\FormFieldFactoryInterface $formFieldFactory
	 */
	public function __construct(StackProviderInterface $stackProvider, FormFieldFactoryInterface $formFieldFactory)
	{
		$this->stackProvider = $stackProvider;
		$this->formFieldFactory = $formFieldFactory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function attach(string $stackName, Container $container, ?ValueCollectionInterface $defaultValues = NULL, ?string $group = NULL): void
	{
		$stack = $this->stackProvider->getStack($stackName);

		/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute */
		foreach ($stack->getAttributes() as $attribute) {
			if (TRUE === $attribute->getExtra(FormFieldOptions::OMIT)) {
				continue;
			}

			if (NULL !== $group && !in_array($group, (array) ($attribute->getExtra(FormFieldOptions::GROUPS) ?? []), TRUE)) {
				continue;
			}

			$formField = $this->formFieldFactory->create($attribute, NULL !== $defaultValues ? $this->getValue($defaultValues, $attribute->getName()) : NULL);
			$container[$attribute->getExtra(FormFieldOptions::NAME) ?? $attribute->getName()] = $formField;
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function mapValues(string $stackName, $values, ?ValueCollectionInterface $valueCollection): ValueCollectionInterface
	{
		$stack = $this->stackProvider->getStack($stackName);
		$valueCollection = $valueCollection ?? new ($stack->getValueCollectionClassName());
		$values = $values instanceof Traversable ? iterator_to_array($values) : (array) $values;

		/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute */
		foreach ($stack->getAttributes() as $attribute) {
			if (TRUE === $attribute->getExtra(FormFieldOptions::OMIT)) {
				continue;
			}

			$fieldName = $attribute->getExtra(FormFieldOptions::NAME) ?? $attribute->getName();

			if (!array_key_exists($fieldName, $values)) {
				continue;
			}

			$valueCollection->setValue($attribute->getName(), $values[$fieldName]);
		}

		return $valueCollection;
	}

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface $valueCollection
	 * @param string $name
	 *
	 * @return mixed|NULL
	 */
	private function getValue(ValueCollectionInterface $valueCollection, string $name)
	{
		try {
			return $valueCollection->getValue($name);
		} catch (AttributeValueException $e) {
			return NULL;
		}
	}
}
