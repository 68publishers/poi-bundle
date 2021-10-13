<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;

interface ValidationRuleApplicatorInterface
{
	/**
	 * @param \Nette\Forms\IControl                                        $control
	 * @param \SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface $attribute
	 *
	 * @return void
	 */
	public function applyRules(IControl $control, AttributeInterface $attribute): void;
}
