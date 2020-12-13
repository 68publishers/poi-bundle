<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

final class ValidatorRegistry
{
	/** @var callable[]  */
	private $validators;

	/**
	 * @param array $validators
	 */
	public function __construct(array $validators)
	{
		$this->validators = (static function (callable ...$validators) {
			return $validators;
		})(...$validators);
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function __invoke($value): bool
	{
		foreach ($this->validators as $validator) {
			if (!$validator($value)) {
				return FALSE;
			}
		}

		return TRUE;
	}
}
