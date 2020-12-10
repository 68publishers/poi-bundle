<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\DI;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseType;
use SixtyEightPublishers\PoiBundle\DbalType\Attributes\AttributesType;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ObjectValueCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\LazyAttributeCollection;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionFactoryInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\ArrayValueCollectionSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\ObjectValueCollectionSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\AttributeValueCollectionSerializer;

final class PoiBundleExtension extends CompilerExtension implements DatabaseTypeProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'attributes' => Expect::arrayOf(Expect::structure([
				'factory' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->required()->before(static function ($factory) {
					return $factory instanceof Statement ? $factory : new Statement($factory);
				}),
				'value_collection_class' => Expect::string(ObjectValueCollection::class)->assert(static function (string $className) {
					return is_a($className, ObjectValueCollection::class, TRUE) || is_subclass_of($className, ObjectValueCollection::class, TRUE);
				}),
			])),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		foreach ($this->config->attributes as $name => $attributeConfig) {
			$normalizedName = str_replace('\\', '_', $name);

			$builder->addDefinition($this->prefix('attribute.collection.factory.' . $normalizedName))
				->setType(AttributeCollectionFactoryInterface::class)
				->setFactory($attributeConfig->factory)
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attribute.collection.lazy.' . $normalizedName))
				->setType(AttributeCollectionInterface::class)
				->setFactory(LazyAttributeCollection::class, [
					$this->prefix('@attribute.collection.factory' . $normalizedName),
				])
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attribute.value.collection_serializer.' . $normalizedName . '.array'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(ArrayValueCollectionSerializer::class)
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attribute.value.collection_serializer.' . $normalizedName . '.attribute'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(AttributeValueCollectionSerializer::class, [
					$this->prefix('@attribute.value.collection_serializer.' . $normalizedName . '.array'),
					$this->prefix('@attribute.collection.lazy.' . $normalizedName),
				])
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attribute.value.collection_serializer.' . $normalizedName . '.object'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(ObjectValueCollectionSerializer::class, [
					$this->prefix('@attribute.value.collection_serializer.' . $normalizedName . '.attribute'),
					$attributeConfig->value_collection_class,
				])
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attribute.value.collection_serializer.' . $normalizedName))
				->setType(CollectionSerializerInterface::class)
				->setFactory($this->prefix('@attribute.value.collection_serializer.' . $normalizedName . '.object'))
				->setAutowired(FALSE);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDatabaseTypes(): array
	{
		return array_map(function (string $name) {
			return new DatabaseType($name, AttributesType::class, NULL, [
				AttributesType::CONTEXT_KEY_NAME => $name,
				AttributesType::CONTEXT_KEY_SERVICE_NAME => $this->prefix('attribute.value.collection_serializer.' . str_replace('\\', '_', $name)),
			]);
		}, array_keys((array) $this->config->attributes));
	}
}