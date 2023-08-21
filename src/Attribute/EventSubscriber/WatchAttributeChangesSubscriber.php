<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\EventSubscriber;

use ReflectionClass;
use Doctrine\ORM\Events;
use ReflectionException;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventSubscriber;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
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
	 * @throws ReflectionException
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
	{
		$metadata = $eventArgs->getClassMetadata();
		$cache = $eventArgs->getEntityManager()->getConfiguration()->getMetadataCache();

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
	 * @throws ReflectionException
	 */
	public function preFlush(object $entity, PreFlushEventArgs $args): void
	{
		$em = $args->getEntityManager();

		/** @var \Doctrine\ORM\Mapping\ClassMetadataFactory $metadataFactory */
		$metadataFactory = $em->getMetadataFactory();
        $cache = $em->getConfiguration()->getMetadataCache();

		$metadata = $metadataFactory->getMetadataFor(get_class($entity));
		$fieldMetadata = $this->getAttributeFieldMetadata($metadata, $cache);

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
     * @throws ReflectionException
     * @throws MappingException
     * @throws InvalidArgumentException
     */
    private function getAttributeFieldMetadata(ClassMetadata $metadata, ?CacheItemPoolInterface $cache): array
	{
		$cacheKey = str_replace('\\', '_', $metadata->getName()) . '__attributes';

		if (array_key_exists($cacheKey, $this->localStorage)) {
			return $this->localStorage[$cacheKey];
		}

        $cacheItem = $cache ? $cache->getItem($cacheKey) : NULL;

		if (NULL !== $cacheItem && $cacheItem->isHit()) {
			return $this->localStorage[$cacheKey] = $cacheItem->get();
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

		if (NULL !== $cacheItem) {
			$cacheItem->set($fields);
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
