<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\WC\Options\OptionsInterface;

abstract class AbstractOptionsOrConditionDefinition implements ConditionDefinitionInterface
{
	/**
	 * @var string
	 */
	protected $textDomain;

	/**
	 * @var OptionsInterface
	 */
	protected $options;

	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param OptionsInterface $options
	 */
	public function __construct(string $textDomain, OptionsInterface $options)
	{
		$this->textDomain = $textDomain;
		$this->options = $options;
	}

	/**
	 * returns options operator for the given settings
	 *
	 * @param array $settings
	 * @return string
	 */
	public function getOptionsOperator(array $settings): string
	{
		return Operator::OR;
	}

	/**
	 * returns options for the given settings
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getOptions(array $settings): array
	{
		return $settings[$this->getKey()] ?? [];
	}

	/**
	 * returns form fields
	 *
	 * @return array
	 */
	public function getFormFields(): array
	{
		return [
			$this->getKey() => [
				'id' => $this->getKey(),
				'type' => 'multiselect',
				'title' => $this->getName(),
				'desc' => __('Empty value means that this condition is not going to be considered', $this->textDomain),
				'class' => 'wc-enhanced-select',
				'options' => $this->options->getOptions(),
				'custom_attributes' => [
					'data-condition-title' => $this->getName(),
				],
			],
		];
	}

	/**
	 * returns option key
	 *
	 * @return string
	 */
	abstract protected function getKey(): string;

	/**
	 * returns option name
	 *
	 * @return string
	 */
	abstract protected function getName(): string;
}
