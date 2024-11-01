<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\Rule\Matcher\ToTime as MatcherToTime;

class ToTime implements ConditionDefinitionInterface
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
		return MatcherToTime::class;
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
		if (false === empty($settings['to_time'])) {
			$options['to_time'] = $settings['to_time'];
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
			'to_time' => [
				'id' => 'to_time',
				'type' => 'time',
				'title' => __('To Time', $this->textDomain),
				'custom_attributes' => [
					'data-condition-title' => __('To Time', $this->textDomain),
				],
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => ['options' => ['regexp' => '/^\d{1,2}:\d{1,2}$/i']],
				'optional' => true,
			],
		];
	}
}
