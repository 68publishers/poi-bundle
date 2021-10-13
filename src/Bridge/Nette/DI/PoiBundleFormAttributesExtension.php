<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Bridge\Nette\DI;

use Nette\Utils\Finder;
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\PoiBundle\Exception\RuntimeException;

final class PoiBundleFormAttributesExtension extends CompilerExtension
{
	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\PoiBundle\Exception\RuntimeException
	 */
	public function loadConfiguration(): void
	{
		if (0 >= count($this->compiler->getExtensions(PoiBundleAttributeExtension::class))) {
			throw new RuntimeException(sprintf(
				'The extension %s can be used only with %s.',
				static::class,
				PoiBundleAttributeExtension::class
			));
		}

		foreach (array_keys(iterator_to_array(Finder::findFiles('*.neon')->from(__DIR__ . '/config/form_attributes'))) as $filename) {
			$this->loadDefinitionsFromConfig(
				$this->loadFromFile($filename)['services']
			);
		}
	}
}
