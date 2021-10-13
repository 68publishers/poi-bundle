<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Value\ValueSerializer;

use DateTimeImmutable;

final class DateTimeImmutableValueSerializer implements ValueSerializerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function serialize($value): ?string
	{
		if (NULL === $value) {
			return NULL;
		}

		assert($value instanceof DateTimeImmutable);

		return $value->format(DateTimeImmutable::ATOM);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception
	 */
	public function deserialize($value): ?DateTimeImmutable
	{
		if (NULL !== $value) {
			$value = new DateTimeImmutable($value);
		}

		return $value;
	}
}
