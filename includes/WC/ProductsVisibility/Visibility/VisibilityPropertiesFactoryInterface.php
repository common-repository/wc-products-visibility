<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Visibility;

interface VisibilityPropertiesFactoryInterface
{
	/**
	 * sets list of rule settings and returns itself
	 *
	 * @param array $listOfRuleSettings
	 * @return VisibilityPropertiesFactoryInterface
	 */
	public function withListOfRuleSettings(array $listOfRuleSettings): VisibilityPropertiesFactoryInterface;

	/**
	 * returns visibility properties for a given product
	 *
	 * @param int $productId
	 * @return VisibilityProperties
	 */
	public function create(int $productId): VisibilityProperties;
}
