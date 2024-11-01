<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\WC\ProductsVisibility\Visibility\VisibilityPropertiesFactory;

class Hooks
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * @var VisibilityPropertiesFactory
	 */
	protected $visibilityPropertiesFactory;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param LoggerInterface $logger
	 * @param VisibilityPropertiesFactory $visibilityPropertiesFactory
	 */
	public function __construct(
		string $id,
		LoggerInterface $logger,
		VisibilityPropertiesFactory $visibilityPropertiesFactory
	) {
		$this->id = $id;
		$this->logger = $logger;
		$this->visibilityPropertiesFactory = $visibilityPropertiesFactory;
	}

	/**
	 * registers plugin
	 *
	 * @return void
	 */
	public function register(): void
	{
		if (is_admin()) {
			return;
		}

		add_filter('woocommerce_product_is_visible', [$this, 'filterProductVisibility'], PHP_INT_MAX, 2);
		add_filter('woocommerce_variation_is_visible', [$this, 'filterVariationVisibility'], PHP_INT_MAX, 2);
		add_filter('woocommerce_dropdown_variation_attribute_options_args', [$this, 'filterVariationAttributeOptionsArgs'], PHP_INT_MAX, 1);
	}

	/**
	 * returns true when a given product should be visible
	 *
	 * @param bool $visible
	 * @param int $productId
	 * @return boolean
	 */
	public function filterProductVisibility(bool $visible, int $productId): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'filterProductVisibility, visible: %d, product ID: %d', $visible, $productId);

		$visible = $this->visibilityPropertiesFactory->create($productId)->getCatalogVisibility()->isVisible($visible);

		$this->logger->debug(__FILE__, __LINE__, 'filterProductVisibility, result visible: %d', $visible);

		return $visible;
	}

	/**
	 * returns true when a given variation should be visible
	 *
	 * @param bool $visible
	 * @param int $variationProductId
	 * @return boolean
	 */
	public function filterVariationVisibility(bool $visible, int $variationProductId): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'filterVariationVisibility, visible: %d, variation product ID: %d', $visible, $variationProductId);

		$visible = $this->visibilityPropertiesFactory->create($variationProductId)->getVariationVisiblity()->isVisible($visible);

		$this->logger->debug(__FILE__, __LINE__, 'filterVariationVisibility, result visible: %d', $visible); // @codingStandardsIgnoreLine

		return $visible;
	}

	/**
	 * returns an filtered array of arguments
	 *
	 * @param array $args
	 * @return array
	 */
	public function filterVariationAttributeOptionsArgs(array $args): array
	{
		if (
			empty($args['options']) ||
			empty($args['attribute']) ||
			empty($args['product']) ||
			false === is_a($args['product'], 'WC_Product_Variable')
		) {
			$this->logger->debug(__FILE__, __LINE__, 'filterVariationAttributeOptionsArgs, invalid args: %s', print_r($args, true));

			return $args;
		}

		$this->logger->debug(__FILE__, __LINE__, 'filterVariationAttributeOptionsArgs, options: %s, attribute: %s', print_r($args['options'], true), $args['attribute']); // phpcs:ignore

		$options = $args['options'];
		$attribute = $args['attribute'];
		$product = $args['product'];

		$variations = $product->get_children();
		foreach ($variations as $variationId) {
			$options = $this->removeVariationAttributeValueFromOptions($options, $attribute, $variationId);
		}

		$this->logger->debug(__FILE__, __LINE__, 'filterVariationAttributeOptionsArgs, new optons: %s', print_r($options, true)); // phpcs:ignore

		$args['options'] = $options;

		return $args;
	}

	/**
	 * returns options with the attribute value removed from it
	 *
	 * @param array $options
	 * @param string $attribute
	 * @param int $variationId
	 * @return array
	 */
	protected function removeVariationAttributeValueFromOptions(array $options, string $attribute, int $variationId): array
	{
		if ($this->filterVariationVisibility(true, $variationId)) {
			return $options;
		}

		$variation = wc_get_product($variationId);
		if (empty($variation)) {
			$this->logger->error(__FILE__, __LINE__, 'removeVariationAttributeValueFromOptions, variation product has not been found'); // phpcs:ignore

			return $options;
		}

		$attributes = $variation->get_attributes();
		$attributeValue = $attributes[$attribute] ?? '';

		$this->logger->debug(__FILE__, __LINE__, 'removeVariationAttributeValueFromOptions, options: %s, attribute: %s, attributeValue: %s, variationId: %d', print_r($options, true), $attribute, $attributeValue, $variationId); // phpcs:ignore

		$newOptions = [];
		foreach ($options as $optionValue) {
			if ($optionValue === $attributeValue) {
				continue;
			}

			$newOptions[] = $optionValue;
		}

		$this->logger->debug(__FILE__, __LINE__, 'removeVariationAttributeValueFromOptions, new optons: %s, variationId: %d', print_r($newOptions, true), $variationId); // phpcs:ignore

		return $newOptions;
	}
}
