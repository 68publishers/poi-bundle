<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Mapping;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use SixtyEightPublishers\PoiBundle\Localization\Annotation\Locale as LocaleAnnotation;

final class AnnotationLocaleMappingAdapter implements LocaleMappingAdapterInterface
{
	private Reader $reader;

	/**
	 * @param \Doctrine\Common\Annotations\Reader $reader
	 */
	public function __construct(Reader $reader)
	{
		$this->reader = $reader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocaleProperties(ClassMetadata $metadata): array
	{
		$properties = [];
		$reflectionClass = $metadata->getReflectionClass();

		if ($metadata->isMappedSuperclass || !$reflectionClass->isInstantiable()) {
			return $properties;
		}

		foreach ($reflectionClass->getProperties() as $property) {
			if (NULL !== $this->reader->getPropertyAnnotation($property, LocaleAnnotation::class)) {
				$property->setAccessible(TRUE);
				$properties[] = $property;
			}
		}

		return $properties;
	}
}
