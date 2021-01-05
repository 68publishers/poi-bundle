<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

interface TranslationInterface
{
	/**
	 * @return string
	 */
	public function getLocale(): string;

	/**
	 * @return string
	 */
	public function getField(): string;

	/**
	 * @return string
	 */
	public function getContent(): string;
}
