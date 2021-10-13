<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\FieldFactory;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;

interface FormFieldFactoryInterface
{
	/**
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 * @param mixed                                                        $value
	 *
	 * @return \Nette\Forms\IControl
	 */
	public function create(AttributeInterface $attribute, $value): IControl;
}
