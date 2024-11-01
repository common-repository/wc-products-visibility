<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\OnSale as MatcherOnSale;

class OnSale implements ConditionDefinitionInterface
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
		return MatcherOnSale::class;
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
		if (isset($settings['on_sale'])) {
			$options['on_sale'] = $settings['on_sale'];
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
			'on_sale' => [
				'id' => 'on_sale',
				'type' => 'checkbox',
				'title' => __('On Sale', $this->textDomain),
				'desc' => __('Matches products that are currently on sale', $this->textDomain),
				'custom_attributes' => [
					'data-condition-title' => __('On Sale', $this->textDomain),
				],
			],
		];
	}
}
