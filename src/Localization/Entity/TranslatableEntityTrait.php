<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

trait TranslatableEntityTrait
{
	use LocalePropertyTrait;

	/**
	 * Map this field to the related collection in en entity
	 *
	 * @var \Doctrine\Common\Collections\Collection|\SixtyEightPublishers\PoiBundle\Localization\Entity\AbstractTranslation[]
	 */
	protected $translations;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface $translation
	 *
	 * @return void
	 */
	public function addTranslation(TranslationInterface $translation): void
	{
		if (!$this->translations->contains($translation)) {
			$this->translations->add($translation);
		}
	}

	/**
	 * @param string $locale
	 * @param string $field
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslationInterface|null
	 */
	public function getTranslationEntity(string $locale, string $field): ?TranslationInterface
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
	public function getTranslation(string $field, ?string $locale = NULL, bool $useFallback = TRUE): string
	{
		$entity = $this->getTranslationEntity($locale ?? $this->getLocaleObject()->getLocale(), $field);

		if (NULL !== $entity) {
			return $entity->getContent();
		}

		if (!$useFallback) {
			return '';
		}

		foreach ($this->getLocaleObject()->getFallbackLocales() as $fallbackLocale) {
			$translation = $this->getTranslation($field, $fallbackLocale, FALSE);

			if (!empty($translation)) {
				return $translation;
			}
		}

		return '';
	}
}
