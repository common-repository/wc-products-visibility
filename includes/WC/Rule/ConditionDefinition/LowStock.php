<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\LowStock as MatcherLowStock;

class LowStock implements ConditionDefinitionInterface
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
		return MatcherLowStock::class;
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
		if (isset($settings['low_stock'])) {
			$options['low_stock'] = $settings['low_stock'];
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
			'low_stock' => [
				'id' => 'low_stock',
				'type' => 'checkbox',
				'title' => __('Low Stock', $this->textDomain),
				'desc' => __('Matches products with the stock quantity below low stock threshold', $this->textDomain),
				'custom_attributes' => [
					'data-condition-title' => __('Lock Stock', $this->textDomain),
				],
			],
		];
	}
}
