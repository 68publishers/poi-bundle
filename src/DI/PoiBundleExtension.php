<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\DI;

use Nette\Utils\Finder;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseType;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMapping;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\Stack;
use SixtyEightPublishers\PoiBundle\Attribute\Stack\StackInterface;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ObjectValueCollection;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMappingProviderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\LazyAttributeCollection;
use SixtyEightPublishers\PoiBundle\Attribute\DbalType\Attributes\AttributesType;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionFactoryInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\CollectionSerializerInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\ArrayValueCollectionSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\ObjectValueCollectionSerializer;
use SixtyEightPublishers\PoiBundle\Attribute\Value\CollectionSerializer\AttributeValueCollectionSerializer;

final class PoiBundleExtension extends CompilerExtension implements DatabaseTypeProviderInterface, EntityMappingProviderInterface
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
				'dbal_type' => Expect::bool(TRUE),
			])),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		foreach (array_keys(iterator_to_array(Finder::findFiles('*.neon')->from(__DIR__ . '/../config'))) as $filename) {
			$this->loadDefinitionsFromConfig(
				$this->loadFromFile($filename)['services']
			);
		}

		$builder = $this->getContainerBuilder();

		foreach ($this->config->attributes as $name => $attributeConfig) {
			$this->registerStack($name, $attributeConfig);
		}

		$builder->getDefinition($this->prefix('attributes.stack_provider.default'))
			->setArguments([
				array_values($builder->findByType(StackInterface::class)),
			]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDatabaseTypes(): array
	{
		return array_map(
			static function (string $name) {
				return new DatabaseType($name, AttributesType::class, NULL, [
					AttributesType::CONTEXT_KEY_NAME => $name,
				]);
			},
			array_keys(array_filter($this->config->attributes, static function (object $config) {
				return $config->dbal_type;
			}))
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityMappings(): array
	{
		return [
			new EntityMapping(EntityMapping::DRIVER_ANNOTATIONS, 'SixtyEightPublishers\PoiBundle\Localization\Entity', __DIR__ . '/../Localization/Entity'),
		];
	}

	/**
	 * @param string $name
	 * @param object $config
	 *
	 * @return void
	 */
	private function registerStack(string $name, object $config): void
	{
		$builder = $this->getContainerBuilder();
		$normalizedName = strtolower(str_replace('\\', '_', $name));

		$builder->addDefinition($this->prefix('attributes.attribute_collection_factory.' . $normalizedName))
			->setType(AttributeCollectionFactoryInterface::class)
			->setFactory($config->factory)
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('attributes.attribute_collection.lazy.' . $normalizedName))
			->setType(AttributeCollectionInterface::class)
			->setFactory(LazyAttributeCollection::class, [
				$this->prefix('@attributes.attribute_collection_factory.' . $normalizedName),
			])
			->setAutowired(FALSE);

		if ($config->dbal_type) {
			$builder->addDefinition($this->prefix('attributes.value_collection_serializer.' . $normalizedName . '.array'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(ArrayValueCollectionSerializer::class)
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attributes.value_collection_serializer.' . $normalizedName . '.attribute'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(AttributeValueCollectionSerializer::class, [
					$this->prefix('@attributes.value_collection_serializer.' . $normalizedName . '.array'),
					$this->prefix('@attributes.attribute_collection.lazy.' . $normalizedName),
				])
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attributes.value_collection_serializer.' . $normalizedName . '.object'))
				->setType(CollectionSerializerInterface::class)
				->setFactory(ObjectValueCollectionSerializer::class, [
					$this->prefix('@attributes.value_collection_serializer.' . $normalizedName . '.attribute'),
					$config->value_collection_class,
				])
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix('attributes.value_collection_serializer.' . $normalizedName))
				->setType(CollectionSerializerInterface::class)
				->setFactory($this->prefix('@attributes.value_collection_serializer.' . $normalizedName . '.object'))
				->setAutowired(FALSE);
		}

		$builder->addDefinition($this->prefix('attributes.stack.' . $normalizedName))
			->setType(StackInterface::class)
			->setFactory(Stack::class, [
				'name' => $name,
				'attributeCollection' => $this->prefix('@attributes.attribute_collection.lazy.' . $normalizedName),
				'valueCollectionClassName' => $config->dbal_type ? $config->value_collection_class : NULL,
				'collectionSerializer' => $config->dbal_type ? $this->prefix('@attributes.value_collection_serializer.' . $normalizedName) : NULL,
			]);
	}
}
