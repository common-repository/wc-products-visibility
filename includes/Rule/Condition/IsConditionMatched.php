<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

class IsConditionMatched
{
	/**
	 * returns true when operator is OR and there is a least one match
	 * returns true when operator is AND and number of matches is equal to number of items
	 *
	 * @param string $operator
	 * @param int $numberOfItems
	 * @param int $numberOfMatches
	 * @return boolean
	 */
	public static function isMatched(string $operator, int $numberOfItems, int $numberOfMatches): bool
	{
		$isMatched = false;

		if ($operator === Operator::OR && $numberOfMatches > 0) {
			$isMatched = true;
		} elseif ($numberOfMatches === $numberOfItems) {
			$isMatched = true;
		}

		return $isMatched;
	}
}
