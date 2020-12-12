<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

interface AttributeBuilderFactoryInterface
{
	/**
	 * @param string|NULL $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function create(?string $name = NULL): AttributeBuilderInterface;
}
