<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory;

use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;

interface ChainableFormFieldFactoryInterface extends FormFieldFactoryInterface
{
	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 *
	 * @return bool
	 */
	public function canCreate(AttributeInterface $attribute): bool;
}
