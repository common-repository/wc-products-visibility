<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\OnBackorder as MatcherOnBackorder;

class OnBackorder implements ConditionDefinitionInterface
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
		return MatcherOnBackorder::class;
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
		if (isset($settings['on_backorder'])) {
			$options['on_backorder'] = $settings['on_backorder'];
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
			'on_backorder' => [
				'id' => 'on_backorder',
				'type' => 'checkbox',
				'title' => __('On Backorder', $this->textDomain),
				'desc' => __('Matches products that are currently on backorder', $this->textDomain),
				'custom_attributes' => [
					'data-condition-title' => __('On Backorder', $this->textDomain),
				],
			],
		];
	}
}
