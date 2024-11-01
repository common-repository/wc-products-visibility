<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Rule\Condition\ConditionInterface;

interface ItemMatcherInterface
{
	/**
	 * returns true when items match a given condition
	 *
	 * @param array $item
	 * @param ConditionInterface $condition
	 * @return boolean
	 */
	public function matchItem(array $item, ConditionInterface $condition): bool;
}
