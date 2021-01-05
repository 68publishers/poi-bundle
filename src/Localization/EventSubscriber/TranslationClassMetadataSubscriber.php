<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\EventSubscriber;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use SixtyEightPublishers\PoiBundle\Localization\Entity\AbstractTranslation;

final class TranslationClassMetadataSubscriber implements EventSubscriber
{
	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
		];
	}

	/**
	 * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 *
	 * @return void
	 * @throws \Doctrine\ORM\Mapping\MappingException
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
	{
		$metadata = $eventArgs->getClassMetadata();

		if (!is_subclass_of($metadata->getName(), AbstractTranslation::class, TRUE)) {
			return;
		}

		$uniqueColumns = array_merge([
			$metadata->getFieldMapping('locale')['columnName'],
			$metadata->getFieldMapping('field')['columnName'],
		], array_map(static function (array $joinColumn) {
			return $joinColumn['name'];
		}, $metadata->getAssociationMapping('object')['joinColumns']));

		$indexPostfix = strtoupper(sprintf(
			'_%s_%s',
			$metadata->table['name'],
			implode('_', $uniqueColumns)
		));

		$metadata->table['uniqueConstraints']['IDX' . $indexPostfix] = $metadata->table['uniqueConstraints']['UNIQ' . $indexPostfix] = [
			'columns' => $uniqueColumns,
		];
	}
}
