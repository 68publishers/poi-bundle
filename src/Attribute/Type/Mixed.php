<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Type;

final class Mixed implements TypeInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function setNullable(bool $nullable): TypeInterface
	{
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isNullable(): bool
	{
		return TRUE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
	}
}
