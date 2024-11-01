<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Matcher\ItemOptionMatcherInterface;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatcherInterface;
use WC_Product;

class Taxonomy implements ItemOptionMatcherInterface
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
	public function __construct(
		LoggerInterface $logger = null,
		TaxonomyMatcherInterface $taxonomyMatcher = null
	) {
		$this->logger = $logger ?? new NullLogger();
		$this->taxonomyMatcher = $taxonomyMatcher ?? new TaxonomyMatcher($logger);
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
		if (empty($item['data']) || false === ($item['data'] instanceof WC_Product) || false === is_string($option)) {
			return false;
		}

		$product = $item['data'];

		$this->logger->debug(__FILE__, __LINE__, 'Match product ID: %d with option: %s', $product->get_id(), $option);

		$taxonomyTermPair = explode('|', (string)$option, 2);
		$taxonomy = $taxonomyTermPair[0];
		$term = $taxonomyTermPair[1] ?? '';

		if (false === $this->taxonomyMatcher->isValid($taxonomy)) {
			$this->logger->debug(__FILE__, __LINE__, 'Taxonomy: %s is invalid', $taxonomy);

			return false;
		}

		return $this->taxonomyMatcher->match($product, $taxonomy, $term);
	}
}
