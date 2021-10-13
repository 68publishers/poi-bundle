<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer;

use DateTimeZone;

final class DateTimeZoneValueSerializer implements ValueSerializerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize($value): ?string
	{
		if (NULL === $value) {
			return NULL;
		}

		assert($value instanceof DateTimeZone);

		return $value->getName();
	}

	/**
	 * {@inheritDoc}
	 */
	public function deserialize($value): ?DateTimeZone
	{
		if (NULL !== $value) {
			$value = new DateTimeZone($value);
		}

		return $value;
	}
}
