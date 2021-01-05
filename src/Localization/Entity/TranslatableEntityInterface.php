<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

use Doctrine\Common\Collections\Collection;

interface TranslatableEntityInterface
{
	/**
	 * @return \Doctrine\Common\Collections\Collection|\SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface[]
	 */
	public function getTranslations(): Collection;

	/**
	 * @param string $locale
	 * @param string $field
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface|NULL
	 */
	public function getTranslation(string $locale, string $field): ?TranslationInterface;

	/**
	 * @param string      $field
	 * @param string|NULL $locale
	 * @param bool        $useFallback
	 *
	 * @return string
	 */
	public function translate(string $field, ?string $locale = NULL, bool $useFallback = TRUE): string;
}
