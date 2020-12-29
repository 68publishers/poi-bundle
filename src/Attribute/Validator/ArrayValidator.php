<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class ArrayValidator
{
	/** @var callable  */
	private $valueValidator;

	/** @var callable|NULL  */
	private $keyValidator;

	/**
	 * @param callable      $valueValidator
	 * @param callable|NULL $keyValidator
	 */
	public function __construct(callable $valueValidator, ?callable $keyValidator = NULL)
	{
		$this->valueValidator = $valueValidator;
		$this->keyValidator = $keyValidator;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function __invoke($value): bool
	{
		if (!is_array($value)) {
			$this->throwTypeError('The value must be array.');
		}

		$valueCallback = $this->valueValidator;
		$keyValidator = $this->keyValidator ?? static function () {
			return NULL;
		};

		foreach ($value as $k => $v) {
			if (!$keyValidator($k)) {
				$this->throwTypeError(sprintf(
					'A key "%s" is invalid.',
					$k
				));
			}

			if (!$valueCallback($v)) {
				$this->throwTypeError(sprintf(
					'The value with a key "%s" is invalid.',
					$k
				));
			}
		}

		return TRUE;
	}

	/**
	 * @param string $message
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	private function throwTypeError(string $message): void
	{
		throw AttributeValueException::validationError($message);
	}
}
