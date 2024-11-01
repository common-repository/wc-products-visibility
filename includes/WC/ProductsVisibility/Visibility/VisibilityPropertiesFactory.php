<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Visibility;

use OneTeamSoftware\Cache\CacheInterface;
use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\WC\ProductsVisibility\ItemsFilter\ItemsSorter;
use OneTeamSoftware\WC\Rule\RuleMatcherInterface;
use WC_Product;

class VisibilityPropertiesFactory implements VisibilityPropertiesFactoryInterface
{
	/**
	 * @var string
	 */
	private const PREFIX_MATCHED = 'matched_';

	/**
	 * @var string
	 */
	private const PREFIX_NOT_MATCHED = 'not_matched_';

	/**
	 * @var RuleMatcherInterface
	 */
	private $ruleMatcher;

	/**
	 * @var CacheInterface
	 */
	private $cache;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var ItemsSorter
	 */
	private $itemsSorter;

	/**
	 * @var array
	 */
	private $listOfRuleSettings;

	/**
	 * constructor
	 *
	 * @param RuleMatcherInterface $ruleMatcher
	 * @param CacheInterface $cache
	 * @param LoggerInterface $logger
	 * @param ItemsSorter $itemsSorter
	 */
	public function __construct(
		RuleMatcherInterface $ruleMatcher,
		CacheInterface $cache,
		LoggerInterface $logger = null,
		ItemsSorter $itemsSorter = null
	) {
		$this->ruleMatcher = $ruleMatcher;
		$this->cache = $cache;
		$this->logger = $logger ?? new NullLogger();
		$this->itemsSorter = $itemsSorter ?? new ItemsSorter();
		$this->itemsSorter->withOrderBy('priority')->withOrderDirection(ItemsSorter::ORDER_DIRECTION_ASC);
		$this->listOfRuleSettings = [];
	}

	/**
	 * sets list of rule settings and returns itself
	 *
	 * @param array $listOfRuleSettings
	 * @return VisibilityPropertiesFactoryInterface
	 */
	public function withListOfRuleSettings(array $listOfRuleSettings): VisibilityPropertiesFactoryInterface
	{
		$this->listOfRuleSettings = $this->itemsSorter->filter($listOfRuleSettings);

		return $this;
	}

	/**
	 * returns true when a given product matches all rules
	 *
	 * @param int $productId
	 * @return VisibilityProperties
	 */
	public function create(int $productId): VisibilityProperties
	{
		$this->logger->debug(__FILE__, __LINE__, 'Create visibility properties for product ID: %d', $productId);

		$cacheKey = $this->getCacheKey($productId);

		$visibilityProperties = new VisibilityProperties();

		if ($this->cache->has($cacheKey)) {
			$this->logger->debug(__FILE__, __LINE__, 'Found cached visibility properties for product ID: %d with cache key: %s', $productId, $cacheKey); // phpcs:ignore

			$this->setVisibilityPropertiesFromArray($visibilityProperties, (array)$this->cache->get($cacheKey));

			return $visibilityProperties;
		}

		$this->updateVisibilityPropertiesForProduct($visibilityProperties, $productId);

		$this->logger->debug(__FILE__, __LINE__, 'Store visibility properties for product ID: %d', $productId);

		$this->cache->set($cacheKey, $visibilityProperties->toArray());

		return $visibilityProperties;
	}

	/**
	 * returns cache key
	 *
	 * @param int $productId
	 * @return string
	 */
	private function getCacheKey(int $productId): string
	{
		return $productId . '_' . hash('sha256', json_encode($this->listOfRuleSettings));
	}

	/**
	 * sets visibility properties from array
	 *
	 * @param VisibilityProperties $visibilityProperties
	 * @param array $data
	 * @return void
	 */
	private function setVisibilityPropertiesFromArray(VisibilityProperties $visibilityProperties, array $data): void
	{
		$this->logger->debug(__FILE__, __LINE__, 'setVisibilityPropertiesFromArray, data: %s', json_encode($data));

		$visibilityProperties
			->getCatalogVisibility()
			->setVisibility($data[VisibilityProperties::CATALOG_VISIBILITY] ?? Visibility::KEEP_AS_IS);

		$visibilityProperties
			->getVariationVisiblity()
			->setVisibility($data[VisibilityProperties::VARIATION_VISIBILITY] ?? Visibility::KEEP_AS_IS);

		$visibilityProperties
			->getUrlVisibility()
			->setVisibility($data[VisibilityProperties::URL_VISIBILITY] ?? Visibility::KEEP_AS_IS);

		$visibilityProperties
			->getRobotsVisibility()
			->setVisibility($data[VisibilityProperties::ROBOTS_VISIBILITY] ?? Visibility::KEEP_AS_IS);
	}

	/**
	 * updates visibility properties for a given product
	 *
	 * @param VisibilityProperties $visibilityProperties
	 * @param int $productId
	 * @return void
	 */
	private function updateVisibilityPropertiesForProduct(
		VisibilityProperties $visibilityProperties,
		int $productId
	): void {
		$this->logger->debug(__FILE__, __LINE__, 'updateVisibilityPropertiesForProduct, productId: %d', $productId);

		$product = wc_get_product($productId);
		if (false === ($product instanceof WC_Product)) {
			$this->logger->debug(__FILE__, __LINE__, 'updateVisibilityPropertiesForProduct, product has not been found');

			return;
		}

		foreach ($this->listOfRuleSettings as $ruleSettings) {
			if (empty($ruleSettings['enabled']) || empty($ruleSettings['id'])) {
				continue;
			}

			$this->updateVisibilityPropertiesFromRule(
				$visibilityProperties,
				$product,
				$ruleSettings
			);
		}
	}

	/**
	 * updates visibility properties for a given rule
	 *
	 * @param VisibilityProperties $visibilityProperties
	 * @param WC_Product $product
	 * @param array $ruleSettings
	 * @return void
	 */
	private function updateVisibilityPropertiesFromRule(
		VisibilityProperties $visibilityProperties,
		WC_Product $product,
		array $ruleSettings
	): void {
		$this->logger->debug(__FILE__, __LINE__, 'updateVisibilityPropertiesFromRule, ruleSettings: %s', json_encode($ruleSettings));

		$prefix = self::PREFIX_NOT_MATCHED;
		if ($this->ruleMatcher->match($product, $ruleSettings)) {
			$prefix = self::PREFIX_MATCHED;
		}

		$keys = [
			VisibilityProperties::CATALOG_VISIBILITY,
			VisibilityProperties::VARIATION_VISIBILITY,
			VisibilityProperties::URL_VISIBILITY,
			VisibilityProperties::ROBOTS_VISIBILITY,
		];

		$data = [];
		foreach ($keys as $key) {
			$data[$key] = $ruleSettings[$prefix . $key] ?? Visibility::KEEP_AS_IS;
		}

		$this->setVisibilityPropertiesFromArray($visibilityProperties, $data);
	}
}
