<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface;

interface AttributeBuilderInterface
{
	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function setName(string $name): self;

	/**
	 * @param bool $nullable
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function setNullable(bool $nullable = TRUE): self;

	/**
	 * @param mixed $defaultValue
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function setDefaultValue($defaultValue): self;

	/**
	 * @param array $extra
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function setExtra(array $extra): self;

	/**
	 * @param array $modifiers
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function modifiable(array $modifiers): self;

	/**
	 * @param callable $validator
	 * @param bool     $validateOnGet
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function validatable(callable $validator, bool $validateOnGet = FALSE): self;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer\ValueSerializerInterface $serializer
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function serializable(ValueSerializerInterface $serializer): self;

	/**
	 * @param callable $callback
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function decorate(callable $callback): self;

	/**
	 * @return void
	 */
	public function reset(): void;

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface
	 */
	public function build(): AttributeInterface;
}
