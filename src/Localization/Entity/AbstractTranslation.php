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
	 *
	 * @ORM\Id
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
	 * @var object
	 */
	protected $object;

	/**
	 * @param object $object
	 * @param string $locale
	 * @param string $filed
	 * @param string $content
	 */
	public function __construct(object $object, string $locale, string $filed, string $content)
	{
		$this->object = $object;
		$this->locale = $locale;
		$this->field = $filed;
		$this->content = $content;
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
	 * @return object
	 */
	public function getObject()
	{
		return $this->object;
	}
}
