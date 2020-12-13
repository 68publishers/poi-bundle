<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization;

use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;

final class Locale implements LocaleInterface
{
	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface  */
	private $translatorLocalizer;

	/** @var string|NULL */
	private $locale;

	/**
	 * @param \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface $translatorLocalizer
	 */
	public function __construct(TranslatorLocalizerInterface $translatorLocalizer)
	{
		$this->translatorLocalizer = $translatorLocalizer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setLocale(string $locale): void
	{
		$this->locale = $locale;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocale(): string
	{
		return $this->locale || $this->translatorLocalizer->getLocale();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFallbackLocales(): array
	{
		return $this->translatorLocalizer->getFallbackLocales();
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return $this->getLocale();
	}
}
