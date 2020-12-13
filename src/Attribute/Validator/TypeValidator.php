<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Attribute\Validator;

final class TypeValidator
{
	public const STRING = 'is_string';
	public const INT = 'is_int';
	public const FLOAT = 'is_float';
	public const BOOL = 'is_bool';
	public const OBJECT = 'is_object';
	public const ARRAY = 'is_array';

	private function __construct()
	{
	}
}
