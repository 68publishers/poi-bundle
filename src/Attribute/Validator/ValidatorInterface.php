<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

interface ValidatorInterface
{
	/**
	 * @param string $type
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Validator\ValidatorInterface|NULL
	 */
	public function lookDown(string $type): ?ValidatorInterface;

	/**
	 * @param mixed $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function validate($value): void;
}
