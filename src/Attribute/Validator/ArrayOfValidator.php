<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class ArrayOfValidator
{
	/** @var callable  */
	private $typeCallback;

	/** @var string  */
	private $typeName;

	/**
	 * @param callable $typeCallback
	 * @param string   $typeName
	 */
	public function __construct(callable $typeCallback, string $typeName)
	{
		$this->typeCallback = $typeCallback;
		$this->typeName = $typeName;
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
			$this->throwTypeError();
		}

		$callback = $this->typeCallback;

		foreach ($value as $item) {
			if (!$callback($item)) {
				$this->throwTypeError();
			}
		}

		return TRUE;
	}

	/**
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	private function throwTypeError(): void
	{
		throw AttributeValueException::validationError(sprintf(
			'The value must be a type of array<%s>.',
			$this->typeName
		));
	}
}
