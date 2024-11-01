<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\Rule\Matcher\ToDate as MatcherToDate;

class ToDate implements ConditionDefinitionInterface
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
		return MatcherToDate::class;
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
		if (false === empty($settings['to_date'])) {
			$options['to_date'] = $settings['to_date'];
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
		wp_enqueue_style('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker');

		wp_add_inline_script('jquery-ui-datepicker', '
			jQuery(window).on("load", function() {
				jQuery(".to_date").datepicker({dateFormat: "yy-mm-dd"});
			});
		');

		return [
			'to_date' => [
				'id' => 'to_date',
				'type' => 'date',
				'title' => __('To Date', $this->textDomain),
				'class' => 'to_date',
				'custom_attributes' => [
					'data-condition-title' => __('To Date', $this->textDomain),
				],
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => ['options' => ['regexp' => '/^\d{4}-\d{1,2}-\d{1,2}$/i']],
				'optional' => true,
			],
		];
	}
}
