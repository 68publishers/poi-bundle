<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\DI;

use Nette\Utils\Finder;
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMapping;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMappingProviderInterface;

final class PoiBundleLocalizationExtension extends CompilerExtension implements EntityMappingProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		foreach (array_keys(iterator_to_array(Finder::findFiles('*.neon')->from(__DIR__ . '/config/localization'))) as $filename) {
			$this->loadDefinitionsFromConfig(
				$this->loadFromFile($filename)['services']
			);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityMappings(): array
	{
		return [
			new EntityMapping(EntityMapping::DRIVER_ANNOTATIONS, 'SixtyEightPublishers\PoiBundle\Localization\Entity', __DIR__ . '/../../../Localization/Entity'),
		];
	}
}
