<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization;

interface LocaleInterface
{
	/**
	 * @param string $locale
	 */
	public function setLocale(string $locale): void;

	/**
	 * @return string
	 */
	public function getLocale(): string;

	/**
	 * @return string[]
	 */
	public function getFallbackLocales(): array;

	/**
	 * @return string
	 */
	public function __toString(): string;
}
