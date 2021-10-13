<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute;

use Nette\Forms\Container;
use SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface;

interface FormAttributeFacadeInterface
{
	/**
	 * @param string                                                                        $stackName
	 * @param \Nette\Forms\Container                                                        $container
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface|NULL $defaultValues
	 * @param string|NULL                                                                   $group
	 *
	 * @return void
	 */
	public function attach(string $stackName, Container $container, ?ValueCollectionInterface $defaultValues = NULL, ?string $group = NULL): void;

	/**
	 * @param string                                                                        $stackName
	 * @param array|\Traversable|object                                                     $values
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface|NULL $valueCollection
	 *
	 * @return \SixtyEightPublishers\PoiBundle\Attribute\Value\ValueCollectionInterface|mixed
	 */
	public function mapValues(string $stackName, $values, ?ValueCollectionInterface $valueCollection): ValueCollectionInterface;
}
