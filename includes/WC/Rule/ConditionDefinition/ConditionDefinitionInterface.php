<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

interface ConditionDefinitionInterface
{
	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string;

	/**
	 * returns options operator for the given settings
	 *
	 * @param array $settings
	 * @return string
	 */
	public function getOptionsOperator(array $settings): string;

	/**
	 * returns options for the given settings
	 *
	 * @param array $settings
	 * @return array
	 */
	public function getOptions(array $settings): array;

	/**
	 * returns form fields
	 *
	 * @return array
	 */
	public function getFormFields(): array;
}
