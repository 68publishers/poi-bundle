<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

use Doctrine\Common\Collections\Collection;
use SixtyEightPublishers\PoiBundle\Localization\Annotation;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Localization\LocaleInterface;

trait TranslatableEntityTrait
{
	// Can't use nested import because of this issue: https://github.com/doctrine/annotations/issues/268
	//use LocalePropertyTrait;

	/**
	 * Map this field to the related collection in en entity
	 *
	 * @var \Doctrine\Common\Collections\Collection|\SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface[]
	 */
	protected Collection $translations;

	/**
	 * @Annotation\Locale()
	 *
	 * @var \SixtyEightPublishers\PoiBundle\Localization\LocaleInterface|NULL
	 */
	protected ?LocaleInterface $localeObject = NULL;

	/**
	 * @return \Doctrine\Common\Collections\Collection|\SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface[]
	 */
	public function getTranslations(): Collection
	{
		return $this->translations;
	}

	/**
	 * @param string $locale
	 * @param string $field
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface|NULL
	 */
	public function getTranslation(string $locale, string $field): ?TranslationInterface
	{
		foreach ($this->translations as $translation) {
			if ($locale === $translation->getLocale() && $field === $translation->getField()) {
				return $translation;
			}
		}

		return NULL;
	}

	/**
	 * @param string      $field
	 * @param string|NULL $locale
	 * @param bool        $useFallback
	 *
	 * @return string
	 */
	public function translate(string $field, ?string $locale = NULL, bool $useFallback = TRUE): string
	{
		$entity = $this->getTranslation($locale ?? $this->getLocaleObject()->getLocale(), $field);

		if (NULL !== $entity) {
			return $entity->getContent();
		}

		if (!$useFallback) {
			return '';
		}

		foreach ($this->getLocaleObject()->getFallbackLocales() as $fallbackLocale) {
			$translation = $this->translate($field, $fallbackLocale, FALSE);

			if (!empty($translation)) {
				return $translation;
			}
		}

		return '';
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Localization\LocaleInterface
	 */
	protected function getLocaleObject(): LocaleInterface
	{
		if (NULL === $this->localeObject) {
			throw new RuntimeException('Locale object is not set.');
		}

		return $this->localeObject;
	}
}
