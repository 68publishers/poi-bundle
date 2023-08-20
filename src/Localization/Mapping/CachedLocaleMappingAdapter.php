<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Mapping;

use ReflectionProperty;
use ReflectionException;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Cache\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

final class CachedLocaleMappingAdapter implements LocaleMappingAdapterInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Localization\Mapping\LocaleMappingAdapterInterface  */
	private $localeAdapter;

	/** @var \Doctrine\ORM\EntityManagerInterface  */
	private $em;

	/** @var array  */
	private $runtimeCache = [];

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
     * @throws InvalidArgumentException|ReflectionException
     */
	public function getLocaleProperties(ClassMetadata $metadata): array
	{
		$className = $metadata->getName();
		$cacheKey = str_replace('\\', '_', $className) . '__locale_fields';

		if (array_key_exists($cacheKey, $this->runtimeCache)) {
			return $this->runtimeCache[$cacheKey];
		}

        $cache = $this->em->getConfiguration()->getMetadataCache();
        $cacheItem = $cache ? $cache->getItem($cacheKey) : NULL;

		if (!$cache instanceof Cache) {
			return $this->runtimeCache[$cacheKey] = $this->localeAdapter->getLocaleProperties($metadata);
		}

        if (NULL !== $cacheItem && $cacheItem->isHit()) {
            return $this->runtimeCache[$cacheKey] = array_map(static function (string $name) use ($className) {
                $property = new ReflectionProperty($className, $name);
                $property->setAccessible(TRUE);

                return $property;
            }, $cacheItem->get());
        }

        $this->runtimeCache[$cacheKey] = $this->localeAdapter->getLocaleProperties($metadata);

        if (NULL !== $cacheItem) {
            $cacheItem->set(array_map(static function (ReflectionProperty $property) {
                return $property->getName();
            }, $this->runtimeCache[$cacheKey]));
        }

		return $this->runtimeCache[$cacheKey];
	}
}
