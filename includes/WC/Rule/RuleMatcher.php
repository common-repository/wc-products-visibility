<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use WC_Product;

class RuleMatcher implements RuleMatcherInterface
{
	/**
	 * @var RuleFactoryInterface
	 */
	private $ruleFactory;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var array<int,array<string,boolean>>
	 */
	private $matches;

	/**
	 * constructor
	 *
	 * @param RuleFactoryInterface $ruleFactory
	 * @param LoggerInterface $logger
	 */
	public function __construct(RuleFactoryInterface $ruleFactory, LoggerInterface $logger = null)
	{
		$this->ruleFactory = $ruleFactory;
		$this->logger = $logger ?? new NullLogger();
		$this->matches = [];
	}

	/**
	 * returns true when a given product matches a rule defined as an array
	 *
	 * @param WC_Product $product
	 * @param array $ruleSettings
	 * @return boolean
	 */
	public function match(WC_Product $product, array $ruleSettings): bool
	{
		$productId = $product->get_id();
		$cacheKey = hash('sha256', json_encode($ruleSettings));

		$this->logger->debug(__FILE__, __LINE__, 'match, product: %d, cache key: %s and rule settings: %s', $productId, $cacheKey, json_encode($ruleSettings)); // phpcs:ignore

		if (isset($this->matches[$productId][$cacheKey])) {
			$this->logger->debug(__FILE__, __LINE__, 'Found previously cached result for product: %d with cache key: %s', $productId, $cacheKey); // phpcs:ignore

			return $this->matches[$productId][$cacheKey];
		}

		$matched = $this->ruleFactory->create($ruleSettings)->match([['data' => $product,],]);
		$this->matches[$productId][$cacheKey] = $matched;

		$this->logger->debug(__FILE__, __LINE__, 'Store match result %d for the future for product: %d and cache key: %s', $matched, $productId, $cacheKey); // phpcs:ignore

		return $matched;
	}
}
