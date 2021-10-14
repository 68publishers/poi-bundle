<?php

declare(strict_types=1);

namespace SixtyEightPublishers\PoiBundle\Localization\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslation implements TranslationInterface
{
	/**
	 * Map the ID field in an inheritor
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=8)
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 * @ORM\Column(type="string", length=100)
	 *
	 * @var string
	 */
	protected $field;

	/**
	 * @ORM\Column(type="text")
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * Map this field to the related object in an inheritor
	 *
	 * @var \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslatableEntityInterface
	 */
	protected $object;

	/**
	 * @param \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslatableEntityInterface $object
	 * @param string                                                                          $locale
	 * @param string                                                                          $field
	 * @param string                                                                          $content
	 */
	public function __construct(TranslatableEntityInterface $object, string $locale, string $field, string $content)
	{
		$this->object = $object;
		$this->locale = $locale;
		$this->field = $field;
		$this->content = $content;

		if (!$object->getTranslations()->contains($this)) {
			$object->getTranslations()->add($this);
		}
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLocale(): string
	{
		return $this->locale;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getField(): string
	{
		return $this->field;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 *
	 * @return void
	 */
	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	/**
	 * @return \SixtyEightPublishers\PoiBundle\Localization\Entity\TranslatableEntityInterface|object
	 */
	public function getObject()
	{
		return $this->object;
	}
}
