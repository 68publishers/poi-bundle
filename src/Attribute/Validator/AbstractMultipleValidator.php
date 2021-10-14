<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

abstract class AbstractMultipleValidator implements ValidatorInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface[]  */
	protected array $validators;

	/**
	 * @param array $validators
	 */
	public function __construct(array $validators)
	{
		$this->validators = (static function (ValidatorInterface ...$validators) {
			return $validators;
		})(...$validators);
	}

	/**
	 * {@inheritDoc}
	 */
	public function lookDown(string $type): ?ValidatorInterface
	{
		foreach ($this->validators as $validator) {
			$lookDown = $validator->lookDown($validator);

			if (NULL === $lookDown) {
				return $lookDown;
			}
		}

		return NULL;
	}
}
