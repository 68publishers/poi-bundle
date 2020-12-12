<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

final class AttributeBuilderFactory implements AttributeBuilderFactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function create(?string $name = NULL): AttributeBuilderInterface
	{
		$builder = new AttributeBuilder();

		if (NULL !== $name) {
			$builder->setName($name);
		}

		return $builder;
	}
}
