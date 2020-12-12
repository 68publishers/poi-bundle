<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

use SixtyEightPublishers\PoiBundle\Attribute\Attribute;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\ModifiableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\ValidatableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\SerializableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

class AttributeBuilder implements AttributeBuilderInterface
{
	/** @var string|NULL */
	private $name;

	/** @var bool  */
	private $nullable = FALSE;

	/** @var mixed|NULL  */
	private $defaultValue;

	/** @var array  */
	private $extra = [];

	/** @var callable[]  */
	private $decorators = [];

	/**
	 * {@inheritDoc}
	 */
	public function setName(string $name): AttributeBuilderInterface
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setNullable(bool $nullable = TRUE): AttributeBuilderInterface
	{
		$this->nullable = $nullable;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultValue($defaultValue): AttributeBuilderInterface
	{
		$this->defaultValue = $defaultValue;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setExtra(array $extra): AttributeBuilderInterface
	{
		$this->extra = $extra;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function modifiable(array $modifiers): AttributeBuilderInterface
	{
		return $this->decorate(static function (AttributeInterface $attribute) use ($modifiers): AttributeInterface {
			return new ModifiableAttribute($attribute, $modifiers);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validatable(callable $validator, bool $validateOnGet = FALSE): AttributeBuilderInterface
	{
		return $this->decorate(static function (AttributeInterface $attribute) use ($validator, $validateOnGet): AttributeInterface {
			return new ValidatableAttribute($attribute, $validator, $validateOnGet);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function serializable(ValueSerializerInterface $serializer): AttributeBuilderInterface
	{
		return $this->decorate(static function (AttributeInterface $attribute) use ($serializer): AttributeInterface {
			return new SerializableAttribute($attribute, $serializer);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function decorate(callable $callback): AttributeBuilderInterface
	{
		$this->decorators[] = $callback;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function reset(): void
	{
		$this->name = $this->defaultValue = NULL;
		$this->nullable = FALSE;
		$this->extra = $this->decorators = [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function build(): AttributeInterface
	{
		if (NULL === $this->name) {
			throw new RuntimeException(sprintf(
				'Please set name by calling the method %s::setName().',
				static::class
			));
		}

		$attribute = new Attribute($this->name, $this->nullable, $this->defaultValue);

		foreach ($this->decorators as $decorator) {
			$attribute = $decorator($attribute);
		}

		$attribute->setExtra($this->extra);
		$this->reset();

		return $attribute;
	}
}
