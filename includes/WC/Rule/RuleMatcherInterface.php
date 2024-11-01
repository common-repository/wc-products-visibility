<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule;

use WC_Product;

interface RuleMatcherInterface
{
	/**
	 * returns true when a given product matches a rule defined as an array
	 *
	 * @param WC_Product $product
	 * @param array $ruleSettings
	 * @return boolean
	 */
	public function match(WC_Product $product, array $ruleSettings): bool;
}
