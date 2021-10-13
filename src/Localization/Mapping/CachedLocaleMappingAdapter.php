<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Mapping;

use ReflectionProperty;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;

final class CachedLocaleMappingAdapter implements LocaleMappingAdapterInterface
{
	private LocaleMappingAdapterInterface $localeAdapter;

	private EntityManagerInterface $em;

	private array $runtimeCache = [];

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Localization\Mapping\LocaleMappingAdapterInterface $localeAdapter
	 * @param \Doctrine\ORM\EntityManagerInterface                                               $em
	 */
	public function __construct(LocaleMappingAdapterInterface $localeAdapter, EntityManagerInterface $em)
	{
		$this->localeAdapter = $localeAdapter;
		$this->em = $em;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocaleProperties(ClassMetadata $metadata): array
	{
		$className = $metadata->getName();
		$cacheKey = $className . '__locale_fields';

		if (array_key_exists($cacheKey, $this->runtimeCache)) {
			return $this->runtimeCache[$cacheKey];
		}

		$cache = $this->em->getMetadataFactory()->getCacheDriver();

		if (!$cache instanceof Cache) {
			return $this->runtimeCache[$cacheKey] = $this->localeAdapter->getLocaleProperties($metadata);
		}

		if ($cache->contains($cacheKey)) {
			return $this->runtimeCache[$cacheKey] = array_map(static function (string $name) use ($className) {
				$property = new ReflectionProperty($className, $name);
				$property->setAccessible(TRUE);

				return $property;
			}, $cache->fetch($cacheKey));
		}

		$fields = $this->localeAdapter->getLocaleProperties($metadata);

		$cache->save($cacheKey, array_map(static function (ReflectionProperty $property) {
			return $property->getName();
		}, $fields));

		return $this->runtimeCache[$cacheKey] = $fields;
	}
}
