<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\Forms\Attribute;

final class FormFieldOptions
{
	# general
	public const NAME = 'name';
	public const LABEL = 'label';
	public const NO_TRANSLATOR = 'no_translator';
	public const PREFERRED_CONTROL = 'preferred_control';

	# custom field factory
	public const FIELD_FACTORY = 'field_factory';

	# don't create a form field
	public const OMIT = 'omit';

	public const GROUPS = 'groups';

	private function __construct()
	{
	}
}
