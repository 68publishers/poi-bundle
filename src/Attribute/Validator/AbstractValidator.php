<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

abstract class AbstractValidator implements ValidatorInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function lookDown(string $type): ?ValidatorInterface
	{
		return $this instanceof $type ? $this : NULL;
	}
}
