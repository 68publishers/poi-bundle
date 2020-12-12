<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Builder;

namespace SixtyEightPublishers\PoiBundle\Attribute\Collection\Builder;

use SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface;
use SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface;

interface AttributeCollectionBuilderInterface
{
	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Builder\AttributeBuilderInterface
	 */
	public function add(string $name): AttributeBuilderInterface;

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Collection\AttributeCollectionInterface
	 */
	public function build(): AttributeCollectionInterface;
}
