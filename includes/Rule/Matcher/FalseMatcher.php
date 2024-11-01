<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Rule\Condition\ConditionInterface;

class FalseMatcher implements MatcherInterface
{
	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return 'false-matcher';
	}

	/**
	 * returns true when items match a given condition
	 *
	 * @param array $items
	 * @param ConditionInterface $condition
	 * @return boolean
	 */
	public function match(array $items, ConditionInterface $condition): bool
	{
		return false;
	}
}
