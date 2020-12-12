<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\EventSubscriber;

use ReflectionClass;
use Doctrine\ORM\Events;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Persistence\Mapping\ClassMetadata;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class WatchAttributeChangesSubscriber implements EventSubscriber
{
	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface  */
	private $stackProvider;

	/** @var array|NULL */
	private $attributesDoctrineTypeNames;

	/** @var array  */
	private $localStorage = [];

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface $stackProvider
	 */
	public function __construct(StackProviderInterface $stackProvider)
	{
		$this->stackProvider = $stackProvider;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::preFlush,
		];
	}

	/**
	 * @internal
	 *
	 * @param \Doctrine\ORM\Event\PreFlushEventArgs $args
	 *
	 * @throws \Doctrine\Persistence\Mapping\MappingException
	 * @throws \ReflectionException
	 */
	public function preFlush(PreFlushEventArgs $args): void
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		/** @var \Doctrine\ORM\Mapping\ClassMetadataFactory $metadataFactory */
		$metadataFactory = $em->getMetadataFactory();

		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			$metadata = $metadataFactory->getMetadataFor(get_class($entity));
			$fieldMetadata = $this->getAttributeFieldMetadata($metadata, $metadataFactory->getCacheDriver());

			if (!empty($fieldMetadata)) {
				$this->updateAttributeFields($entity, $fieldMetadata, $em);
			}
		}
	}

	/**
	 * @param object                               $entity
	 * @param array                                $fieldMetadata
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	private function updateAttributeFields(object $entity, array $fieldMetadata, EntityManagerInterface $em): void
	{
		foreach ($fieldMetadata as $metadata) {
			$classMetadata = $em->getClassMetadata($metadata['class']);

			foreach ($metadata['fields'] as $field) {
				$value = $classMetadata->getFieldValue($entity, $field);

				if (!$value instanceof ValueCollectionInterface) {
					continue;
				}

				if (ValueCollectionInterface::STATE_UPDATED === $value->getState()) {
					$classMetadata->setFieldValue($entity, $field, clone $value);
				}
			}
		}
	}

	/**
	 * @param \Doctrine\Persistence\Mapping\ClassMetadata $metadata
	 * @param \Doctrine\Common\Cache\Cache|NULL           $cache
	 *
	 * @return array
	 * @throws \ReflectionException
	 */
	private function getAttributeFieldMetadata(ClassMetadata $metadata, ?Cache $cache): array
	{
		$cacheKey = $metadata->getName() . '__attributes';

		if (array_key_exists($cacheKey, $this->localStorage)) {
			return $this->localStorage[$cacheKey];
		}

		if (NULL !== $cache && $cache->contains($cacheKey)) {
			return $this->localStorage[$cacheKey] = $cache->fetch($cacheKey);
		}

		$fields = [];
		$typeNames = $this->getAttributesDoctrineTypeNames();

		foreach ($metadata->getFieldNames() as $fieldName) {
			$mapping = $metadata->getFieldMapping($fieldName);

			if (!in_array($mapping['type'], $typeNames, TRUE)) {
				continue;
			}

			$fields[] = [
				'class' => ($metadata->isInheritedField($fieldName) ? new ReflectionClass($mapping['declared']) : $metadata->getReflectionClass())->getName(),
				'fields' => $fieldName,
			];
		}

		if (NULL !== $cache) {
			$cache->save($cacheKey, $fields);
		}

		return $this->localStorage[$cacheKey] = $fields;
	}

	/**
	 * @return string[]
	 */
	private function getAttributesDoctrineTypeNames(): array
	{
		if (NULL === $this->attributesDoctrineTypeNames) {
			$this->attributesDoctrineTypeNames = array_filter(
				array_map(
					static function (StackInterface $stack) {
						return NULL !== $stack->getValueCollectionClassName() ? $stack->getName() : NULL;
					},
					iterator_to_array($this->stackProvider)
				)
			);
		}

		return $this->attributesDoctrineTypeNames;
	}
}
