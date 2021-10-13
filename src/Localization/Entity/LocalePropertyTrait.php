<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

use SixtyEightPublishers\PoiBundle\Localization\Annotation;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Localization\LocaleInterface;

trait LocalePropertyTrait
{
	/**
	 * @Annotation\Locale()
	 *
	 * @var \SixtyEightPublishers\PoiBundle\Localization\LocaleInterface|NULL
	 */
	private ?LocaleInterface $localeObject = NULL;

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
