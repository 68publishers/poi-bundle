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
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

final class WatchAttributeChangesSubscriber implements EventSubscriber
{
	private StackProviderInterface $stackProvider;

	private ?array $attributesDoctrineTypeNames = NULL;

	private array $localStorage = [];

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
			Events::loadClassMetadata,
		];
	}

	/**
	 * @internal
	 *
	 * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 *
	 * @return void
	 * @throws \Doctrine\ORM\Mapping\MappingException
	 * @throws \ReflectionException
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
	{
		$metadata = $eventArgs->getClassMetadata();
		$cache = $eventArgs->getEntityManager()->getMetadataFactory()->getCacheDriver();

		if ($metadata->isMappedSuperclass || !$metadata->getReflectionClass()->isInstantiable() || !count($this->getAttributeFieldMetadata($metadata, $cache))) {
			return;
		}

		$metadata->addEntityListener(Events::preFlush, static::class, Events::preFlush);
	}

	/**
	 * @internal
	 *
	 * @param object                                $entity
	 * @param \Doctrine\ORM\Event\PreFlushEventArgs $args
	 *
	 * @return void
	 * @throws \Doctrine\Persistence\Mapping\MappingException
	 * @throws \ReflectionException
	 */
	public function preFlush(object $entity, PreFlushEventArgs $args): void
	{
		$em = $args->getEntityManager();

		/** @var \Doctrine\ORM\Mapping\ClassMetadataFactory $metadataFactory */
		$metadataFactory = $em->getMetadataFactory();

		$metadata = $metadataFactory->getMetadataFor(get_class($entity));
		$fieldMetadata = $this->getAttributeFieldMetadata($metadata, $metadataFactory->getCacheDriver());

		if (!empty($fieldMetadata)) {
			$this->updateAttributeFields($entity, $fieldMetadata, $em);
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
			$value = $classMetadata->getFieldValue($entity, $metadata['field']);

			if (!$value instanceof ValueCollectionInterface) {
				continue;
			}

			if (ValueCollectionInterface::STATE_UPDATED === $value->getState()) {
				$classMetadata->setFieldValue($entity, $metadata['field'], clone $value);
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
				'field' => $fieldName,
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
