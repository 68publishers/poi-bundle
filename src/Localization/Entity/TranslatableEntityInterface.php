<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

interface TranslatableEntityInterface
{
	/**
	 * @param string      $field
	 * @param string|NULL $locale
	 * @param bool        $useFallback
	 *
	 * @return string
	 */
	public function translate(string $field, ?string $locale = NULL, bool $useFallback = TRUE): string;
}
