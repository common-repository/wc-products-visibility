<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

interface ConditionInterface
{
	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string;

	/**
	 * returns options operator
	 *
	 * @return string
	 */
	public function getOptionsOperator(): string;

	/**
	 * returns items operator
	 *
	 * @return string
	 */
	public function getItemsOperator(): string;

	/**
	 * returns options
	 *
	 * @return string
	 */
	public function getOptions(): array;
}
