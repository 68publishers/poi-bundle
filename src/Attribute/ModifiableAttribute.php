<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class ModifiableAttribute extends AbstractAttributeDecorator
{
	public const MODIFIER_GET_VALUE = 'getValue';
	public const MODIFIER_SET_VALUE = 'setValue';

	/** @var array  */
	private $modifiers;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 * @param callable[]                                                   $modifiers
	 */
	public function __construct(AttributeInterface $attribute, array $modifiers = [])
	{
		parent::__construct($attribute);

		$this->modifiers = $modifiers;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = [])
	{
		if (TRUE === ($context[self::GET_VALUE_CONTEXT_RAW] ?? FALSE)) {
			return parent::getValue($valueCollection);
		}

		return $this->modify(parent::getValue($valueCollection), self::MODIFIER_GET_VALUE);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void
	{
		parent::setValue($valueCollection, $this->modify($value, self::MODIFIER_SET_VALUE));
	}

	/**
	 * @param mixed  $value
	 * @param string $type
	 *
	 * @return mixed
	 */
	private function modify($value, string $type)
	{
		if (NULL === $value && $this->isNullable()) {
			return $value;
		}

		if (isset($this->modifiers[$type]) && is_callable($this->modifiers[$type])) {
			$modifier = $this->modifiers[$type];

			$value = $modifier($value);
		}

		return $value;
	}
}
