<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Matcher\ItemOptionMatcherInterface;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\AttributeMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatcherInterface;
use WC_Product_Variation;

class Attribute implements ItemOptionMatcherInterface
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var TaxonomyMatcherInterface
	 */
	private $taxonomyMatcher;

	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 * @param TaxonomyMatcherInterface $taxonomyMatcher
	 */
	public function __construct(LoggerInterface $logger = null, TaxonomyMatcherInterface $taxonomyMatcher = null)
	{
		$this->logger = $logger ?? new NullLogger();
		$this->taxonomyMatcher = $taxonomyMatcher ?? new AttributeMatcher($logger);
	}

	/**
	 * returns true when item matches a given condition
	 *
	 * @param array $item
	 * @param mixed $option
	 * @return boolean
	 */
	public function matchItemOption(array $item, $option): bool
	{
		if (empty($item['data']) || !($item['data'] instanceof WC_Product_Variation) || !is_string($option)) {
			return false;
		}

		$product = $item['data'];

		$this->logger->debug(__FILE__, __LINE__, 'Match product ID: %d with option: %s', $product->get_id(), $option); // @codingStandardsIgnoreLine

		$taxonomyTermPair = explode('|', (string)$option, 2);
		$taxonomy = $taxonomyTermPair[0] ?? '';
		$term = $taxonomyTermPair[1] ?? '';

		if (false === $this->taxonomyMatcher->isValid($taxonomy)) {
			$this->logger->debug(__FILE__, __LINE__, 'Taxonomy: %s is not an attribute', $taxonomy); // @codingStandardsIgnoreLine

			return false;
		}

		return $this->taxonomyMatcher->match($product, (string)$taxonomy, (string)$term);
	}

	/**
	 * returns true when provided taxonomy is an attribute
	 *
	 * @param string $taxonomy
	 * @return boolean
	 */
	public function isAttributeTaxonomy(string $taxonomy): bool
	{
		return 0 === strpos($taxonomy, 'pa_');
	}
}
