<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher;

use WC_Product;

interface TaxonomyMatcherInterface
{
	/**
	 * returns true when taxonomy and term are valid
	 *
	 * @param string $taxonomy
	 * @return bool
	 */
	public function isValid(string $taxonomy): bool;

	/**
	 * returns true when a given product has a given taxonomy with a given value
	 *
	 * @param WC_Product $product
	 * @param string $taxonomy
	 * @param string $term
	 * @return bool
	 */
	public function match(WC_Product $product, string $taxonomy, string $term): bool;
}
