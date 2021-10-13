<?php

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator;

use Nette\Forms\IControl;
use SixtyEightPublishers\PoiBundle\Attribute\AttributeInterface;

final class ValidationRuleApplicatorRegistry implements ValidationRuleApplicatorInterface
{
	/** @var \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator\ValidationRuleApplicatorInterface[]  */
	private array $validationRuleApplicators;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute\ValidationRuleApplicator\ValidationRuleApplicatorInterface[] $validationRuleApplicators
	 */
	public function __construct(array $validationRuleApplicators)
	{
		$this->validationRuleApplicators = (static fn (ValidationRuleApplicatorInterface ...$validationRuleApplicators) : array => $validationRuleApplicators)(...$validationRuleApplicators);
	}

	/**
	 * {@inheritDoc}
	 */
	public function applyRules(IControl $control, AttributeInterface $attribute): void
	{
		foreach ($this->validationRuleApplicators as $validationRuleApplicator) {
			$validationRuleApplicator->applyRules($control, $attribute);
		}
	}
}
