<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Rule\Condition\ConditionInterface;

interface MatcherInterface
{
	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string;

	/**
	 * returns true when items match a given condition
	 *
	 * @param array $items
	 * @param ConditionInterface $condition
	 * @return boolean
	 */
	public function match(array $items, ConditionInterface $condition): bool;
}
