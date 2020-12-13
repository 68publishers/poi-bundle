<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;

interface LocaleMappingAdapterInterface
{
	/**
	 * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
	 *
	 * @return \ReflectionProperty[]
	 */
	public function getLocaleProperties(ClassMetadata $metadata): array;
}
