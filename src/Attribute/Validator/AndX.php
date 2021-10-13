<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

final class AndX extends AbstractMultipleValidator
{
	/**
	 * {@inheritDoc}
	 */
	public function validate($value): void
	{
		foreach ($this->validators as $validator) {
			$validator->validate($value);
		}
	}
}
