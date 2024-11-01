<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\StockStatus as MatcherStockStatus;

class StockStatus implements ConditionDefinitionInterface
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
		return MatcherStockStatus::class;
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
		if (false === empty($settings['stock_status'])) {
			$options['stock_status'] = $settings['stock_status'];
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
			'stock_status' => [
				'id' => 'stock_status',
				'type' => 'select',
				'title' => __('Stock Status', $this->textDomain),
				'options' => [
					'' => __('Any', $this->textDomain),
					'instock' => __('In Stock', $this->textDomain),
					'outofstock' => __('Out of Stock', $this->textDomain),
				],
				'custom_attributes' => [
					'data-condition-title' => __('Stock Status', $this->textDomain),
				],
			],
		];
	}
}
