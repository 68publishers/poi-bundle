<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\DbalType\Attributes;

use JsonException;
use Nette\DI\Container;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;
use SixtyEightPublishers\PoiBundle\Exception\InvalidArgumentException;
use SixtyEightPublishers\DoctrineBridge\Type\ContainerAwareTypeInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface;

final class AttributesType extends Type implements ContainerAwareTypeInterface
{
	public const CONTEXT_KEY_NAME = 'name';
	public const CONTEXT_KEY_SERVICE_NAME = 'service_name';

	/** @var string|NULL */
	private $name;

	/** @var \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface|NULL */
	private $valueCollectionSerializer;

	/**
	 * @internal
	 *
	 * {@inheritDoc}
	 */
	public function setContainer(Container $container, array $context = []): void
	{
		if (!isset($context[self::CONTEXT_KEY_NAME], $context[self::CONTEXT_KEY_SERVICE_NAME])) {
			throw new InvalidArgumentException(sprintf(
				'Invalid context passed into %s.',
				__METHOD__
			));
		}

		$this->setName($context[self::CONTEXT_KEY_NAME]);
		$this->setValueCollectionSerializer($container->getService($context[self::CONTEXT_KEY_SERVICE_NAME]));
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface $collectionSerializer
	 *
	 * @return void
	 */
	public function setValueCollectionSerializer(CollectionSerializerInterface $collectionSerializer): void
	{
		$this->valueCollectionSerializer = $collectionSerializer;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\RuntimeException
	 */
	public function getName(): string
	{
		if (NULL === $this->name) {
			throw new RuntimeException(sprintf(
				'Please set type\'s name via method %s::setName().',
				static::class
			));
		}

		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Doctrine\DBAL\Types\ConversionException
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (NULL === $value) {
			return NULL;
		}

		try {
			return $this->getValueCollectionSerializer()->serialize($value);
		} catch (JsonException $e) {
			throw ConversionException::conversionFailedSerialization($value, 'json', $e->getMessage());
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Doctrine\DBAL\Types\ConversionException
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform): ?ValueCollectionInterface
	{
		if (NULL === $value || empty($value)) {
			return NULL;
		}

		try {
			return $this->getValueCollectionSerializer()->deserialize($value);
		} catch (JsonException $e) {
			throw ConversionException::conversionFailed($value, $this->getName());
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return $platform->getJsonTypeDeclarationSQL($column);
	}

	/**
	 * {@inheritdoc}
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return TRUE;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\RuntimeException
	 */
	private function getValueCollectionSerializer(): CollectionSerializerInterface
	{
		if (NULL === $this->valueCollectionSerializer) {
			throw new RuntimeException(sprintf(
				'Please set value collection serializer via method %s::setValueCollectionSerializer()',
				static::class
			));
		}

		return $this->valueCollectionSerializer;
	}
}
