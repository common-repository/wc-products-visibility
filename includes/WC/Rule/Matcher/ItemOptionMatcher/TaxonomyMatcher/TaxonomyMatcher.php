<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use WC_Product;

class TaxonomyMatcher implements TaxonomyMatcherInterface
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var array
	 */
	private $validTaxonomies;

	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 * @param array $validTaxonomies
	 */
	public function __construct(LoggerInterface $logger = null, array $validTaxonomies = [])
	{
		$this->logger = $logger ?? new NullLogger();
		$this->validTaxonomies = $validTaxonomies;
	}

	/**
	 * returns true when taxonomy and term are valid
	 *
	 * @param string $taxonomy
	 * @return bool
	 */
	public function isValid(string $taxonomy): bool
	{
		if (empty($taxonomy)) {
			return false;
		}

		if (empty($this->validTaxonomies)) {
			return true;
		}

		return in_array($taxonomy, $this->validTaxonomies, true);
	}

	/**
	 * returns true when a given product has a given taxonomy with a given value
	 *
	 * @param WC_Product $product
	 * @param string $taxonomy
	 * @param string $term
	 * @return boolean
	 */
	public function match(WC_Product $product, string $taxonomy, string $term): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'hasTaxonomyTerm, Product ID: %d, taxonomy: %s, term: %s', $product->get_id(), $taxonomy, $term); //phpcs:ignore

		// may need to add product conditions to reduce this code duplication
		$isMatched = has_term($term, $taxonomy, $product->get_id());

		if (false === $isMatched && 0 !== $product->get_parent_id()) {
			$this->logger->debug(__FILE__, __LINE__, 'Product has not matched, lets try parent product ID: %d', $product->get_parent_id()); // @codingStandardsIgnoreLine

			$isMatched = has_term($term, $taxonomy, $product->get_parent_id());
		}

		$this->logger->debug(__FILE__, __LINE__, 'Product ID: %d has %s matched', $product->get_id(), $isMatched ? '' : 'not');

		return $isMatched;
	}
}
