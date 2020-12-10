<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

interface AttributeInterface
{
	public const EXTRA_KEY_SERIALIZER = 'serializer';

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return bool
	 */
	public function isNullable(): bool;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface $valueCollection
	 *
	 * @return mixed
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function getValue(ValueCollectionInterface $valueCollection);

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface $valueCollection
	 * @param mixed                                                                    $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\AttributeValueException
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void;

	/**
	 * @param array $extra
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface
	 */
	public function setExtra(array $extra): AttributeInterface;

	/**
	 * @param string|NULL $key
	 *
	 * @return array|mixed|NULL
	 */
	public function getExtra(?string $key = NULL);
}
