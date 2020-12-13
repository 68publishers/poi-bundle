<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

use SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException;

final class InstanceOfValidator
{
	/** @var string  */
	private $type;

	/**
	 * @param string $type
	 */
	public function __construct(string $type)
	{
		$this->type = $type;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function __invoke($value): bool
	{
		if (!($value instanceof $this->type)) {
			throw AttributeValueException::validationError(sprintf(
				'The value must be instance of %s',
				$this->type
			));
		}

		return TRUE;
	}
}
