<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Visibility;

class VisibilityProperties
{
	/**
	 * @var string
	 */
	public const CATALOG_VISIBILITY = 'catalog_visibility';

	/**
	 * @var string
	 */
	public const VARIATION_VISIBILITY = 'variation_visibility';

	/**
	 * @var string
	 */
	public const URL_VISIBILITY = 'url_visibility';

	/**
	 * @var string
	 */
	public const ROBOTS_VISIBILITY = 'robots_visibility';

	/**
	 * @var Visibility
	 */
	private $catalogVisibility;

	/**
	 * @var Visibility
	 */
	private $variationVisibility;

	/**
	 * @var Visibility
	 */
	private $urlVisibility;

	/**
	 * @var Visibility
	 */
	private $robotsVisibility;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->catalogVisibility = new Visibility();
		$this->variationVisibility = new Visibility();
		$this->urlVisibility = new Visibility();
		$this->robotsVisibility = new Visibility();
	}

	/**
	 * returns visibility properties as array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			self::CATALOG_VISIBILITY => $this->catalogVisibility->getVisibility(),
			self::VARIATION_VISIBILITY => $this->variationVisibility->getVisibility(),
			self::URL_VISIBILITY => $this->urlVisibility->getVisibility(),
			self::ROBOTS_VISIBILITY => $this->robotsVisibility->getVisibility(),
		];
	}

	/**
	 * returns true when product should be visible in catalog
	 *
	 * @return Visibility
	 */
	public function getCatalogVisibility(): Visibility
	{
		return $this->catalogVisibility;
	}

	/**
	 * returns true when product variations is visible
	 *
	 * @return Visibility
	 */
	public function getVariationVisiblity(): Visibility
	{
		return $this->variationVisibility;
	}

	/**
	 * returns true when product can be accessed via URL
	 *
	 * @return Visibility
	 */
	public function getUrlVisibility(): Visibility
	{
		return $this->urlVisibility;
	}

	/**
	 * returns true when product can be index by the robots
	 *
	 * @return Visibility
	 */
	public function getRobotsVisibility(): Visibility
	{
		return $this->robotsVisibility;
	}
}
