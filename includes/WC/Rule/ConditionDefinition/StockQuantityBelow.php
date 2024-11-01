<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\StockQuantityBelow as MatcherStockQuantityBelow;

class StockQuantityBelow implements ConditionDefinitionInterface
{
	/**
	 * @var string
	 */
	private $textDomain;

	/**
	 * constructor
	 *
	 * @param string $textDomain
	 */
	public function __construct(string $textDomain)
	{
		$this->textDomain = $textDomain;
	}

	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return MatcherStockQuantityBelow::class;
	}

	/**
	 * returns options operator for the given settings
	 *
	 * @param array $settings
	 * @return string
	 */
	public function getOptionsOperator(array $settings): string
	{
		return Operator::AND;
	}

	/**
	 * returns options for the given settings
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getOptions(array $settings): array
	{
		$options = [];
		if (isset($settings['stock_quantity_below'])) {
			$options['stock_quantity_below'] = $settings['stock_quantity_below'];
		}

		return $options;
	}

	/**
	 * returns form fields
	 *
	 * @return array
	 */
	public function getFormFields(): array
	{
		return [
			'stock_quantity_below' => [
				'id' => 'stock_quantity_below',
				'type' => 'number',
				'title' => __('Stock Quantity Below', $this->textDomain),
				'custom_attributes' => [
					'min' => 1,
					'step' => 1,
					'data-condition-title' => __('Stock Quantity Below', $this->textDomain),
				],
				'filter' => FILTER_VALIDATE_INT,
				'filter_options' => ['options' => ['min_range' => 1]],
				'optional' => true,
			],
		];
	}
}
