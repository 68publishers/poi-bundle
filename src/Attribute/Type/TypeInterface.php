<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

interface TypeInterface
{
	/**
	 * @param bool $nullable
	 *
	 * @return $this
	 */
	public function setNullable(bool $nullable): self;

	/**
	 * @return bool
	 */
	public function isNullable(): bool;

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function validate($value): void;
}
