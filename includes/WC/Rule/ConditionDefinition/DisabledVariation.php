<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Rule\Matcher\DisabledVariation as MatcherDisabledVariation;

class DisabledVariation implements ConditionDefinitionInterface
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
		return MatcherDisabledVariation::class;
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
		if (isset($settings['disabled_variation'])) {
			$options['disabled_variation'] = $settings['disabled_variation'];
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
			'disabled_variation' => [
				'id' => 'disabled_variation',
				'type' => 'checkbox',
				'title' => __('Disabled Variation', $this->textDomain),
				'desc' => __('Matches disabled product variations', $this->textDomain),
				'custom_attributes' => [
					'data-condition-title' => __('Disabled Variation', $this->textDomain),
				],
			],
		];
	}
}
