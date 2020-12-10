<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer;

use DateTime;

final class DateTimeValueSerializer implements ValueSerializerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize($value)
	{
		if (NULL === $value) {
			return NULL;
		}

		assert($value instanceof DateTime);

		return $value->format(DateTime::ATOM);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception
	 */
	public function deserialize($value)
	{
		if (NULL !== $value) {
			$value = new DateTime($value);
		}

		return $value;
	}
}
