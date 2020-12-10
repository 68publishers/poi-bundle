<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer;

interface ValueSerializerInterface
{
	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function serialize($value);

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function deserialize($value);
}
