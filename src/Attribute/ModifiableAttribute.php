<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class ModifiableAttribute implements AttributeInterface
{
	public const MODIFIER_GET_VALUE = 'getValue';
	public const MODIFIER_SET_VALUE = 'setValue';

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface  */
	private $attribute;

	/** @var array  */
	private $modifiers;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 * @param callable[]                                                   $modifiers
	 */
	public function __construct(AttributeInterface $attribute, array $modifiers = [])
	{
		$this->attribute = $attribute;
		$this->modifiers = $modifiers;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->attribute->getName();
	}

	/**
	 * {@inheritDoc}
	 */
	public function isNullable(): bool
	{
		return $this->attribute->isNullable();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(ValueCollectionInterface $valueCollection)
	{
		return $this->modify($this->attribute->getValue($valueCollection), self::MODIFIER_GET_VALUE);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void
	{
		$this->attribute->setValue($valueCollection, $this->modify($value, self::MODIFIER_SET_VALUE));
	}

	/**
	 * {@inheritDoc}
	 */
	public function setExtra(array $extra): AttributeInterface
	{
		$this->attribute->setExtra($extra);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getExtra(?string $key = NULL)
	{
		return $this->attribute->getExtra($key);
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
