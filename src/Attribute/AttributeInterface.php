<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute;

use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

interface AttributeInterface
{
	public const GET_VALUE_CONTEXT_RAW = 'raw';

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
	 * @param array                                                                    $context
	 *
	 * @return mixed
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function getValue(ValueCollectionInterface $valueCollection, array $context = []);

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface $valueCollection
	 * @param mixed                                                                    $value
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\PoiBundle\Attribute\Exception\AttributeValueException
	 */
	public function setValue(ValueCollectionInterface $valueCollection, $value): void;

	/**
	 * @param string $type
	 *
	 * @return mixed|\SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface|NULL
	 */
	public function lookDown(string $type): ?AttributeInterface;

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
