<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

use DateTime;
use DateTimeZone;
use DateTimeImmutable;
use SixtyEightPublishers\PoiBundle\Attribute\Attribute;
use SixtyEightPublishers\PoiBundle\Attribute\Type\Mixed;
use SixtyEightPublishers\PoiBundle\Attribute\Type\Instance;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Type\TypeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\ModifiableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\ValidatableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\SerializableAttribute;
use SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\DateTimeValueSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\DateTimeZoneValueSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\DateTimeImmutableValueSerializer;

class AttributeBuilder implements AttributeBuilderInterface
{
	private ?string $name = NULL;

	private ?TypeInterface $type = NULL;

	/** @var mixed|NULL  */
	private $defaultValue;

	private array $extra = [];

	/** @var callable[]  */
	private array $decorators = [];

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
	public function setType(TypeInterface $type): AttributeBuilderInterface
	{
		$this->type = $type;

		if ($type instanceof Instance) {
			switch ($type->getClassName()) {
				case DateTime::class:
					return $this->serializable(new DateTimeValueSerializer());
				case DateTimeImmutable::class:
					return $this->serializable(new DateTimeImmutableValueSerializer());
				case DateTimeZone::class:
					return $this->serializable(new DateTimeZoneValueSerializer());
			}
		}

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
	public function validatable(ValidatorInterface $validator, bool $validateOnGet = FALSE): AttributeBuilderInterface
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
		$this->name = $this->type = $this->defaultValue = NULL;
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

		$attribute = new Attribute($this->name, $this->type ?? new Mixed(), $this->defaultValue);

		foreach ($this->decorators as $decorator) {
			$attribute = $decorator($attribute);
		}

		$attribute->setExtra($this->extra);
		$this->reset();

		return $attribute;
	}
}
