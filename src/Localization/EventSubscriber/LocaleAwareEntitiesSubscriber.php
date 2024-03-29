<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\EventSubscriber;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use SixtyEightPublishers\PoiBundle\Localization\Locale;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use SixtyEightPublishers\PoiBundle\Localization\Mapping\LocaleMappingAdapterInterface;

final class LocaleAwareEntitiesSubscriber implements EventSubscriber
{
	/** @var \SixtyEightPublishers\PoiBundle\Localization\Mapping\LocaleMappingAdapterInterface  */
	private $localeMappingAdapter;

	/** @var \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface  */
	private $translatorLocalizer;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Localization\Mapping\LocaleMappingAdapterInterface $localeMappingAdapter
	 * @param \SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface  $translatorLocalizer
	 */
	public function __construct(LocaleMappingAdapterInterface $localeMappingAdapter, TranslatorLocalizerInterface $translatorLocalizer)
	{
		$this->localeMappingAdapter = $localeMappingAdapter;
		$this->translatorLocalizer = $translatorLocalizer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::prePersist,
			Events::postLoad,
		];
	}

	/**
	 * @internal
	 *
	 * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
	 *
	 * @return void
	 */
	public function prePersist(LifecycleEventArgs $args): void
	{
		$this->setupLocale($args);
	}

	/**
	 * @internal
	 *
	 * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
	 *
	 * @return void
	 */
	public function postLoad(LifecycleEventArgs $args): void
	{
		$this->setupLocale($args);
	}

	/**
	 * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
	 *
	 * @return void
	 */
	private function setupLocale(LifecycleEventArgs $args): void
	{
		$entity = $args->getObject();
		$metadata = $args->getObjectManager()->getClassMetadata(get_class($entity));
		$properties = $this->localeMappingAdapter->getLocaleProperties($metadata);

		if (empty($properties)) {
			return;
		}

		$locale = new Locale($this->translatorLocalizer);

		foreach ($properties as $property) {
			$property->setValue($entity, $locale);
		}
	}
}
