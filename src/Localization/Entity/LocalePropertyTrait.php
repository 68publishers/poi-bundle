<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Localization\LocaleInterface;

trait LocalePropertyTrait
{
	/**
	 * @Annotation\Locale()
	 *
	 * @var \SixtyEightPublishers\PoiBundle\Localization\LocaleInterface|NULL
	 */
	private $locale;

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Localization\LocaleInterface
	 */
	protected function getLocale(): LocaleInterface
	{
		if (NULL === $this->locale) {
			throw new RuntimeException('Locale object is not set.');
		}

		return $this->locale;
	}
}
