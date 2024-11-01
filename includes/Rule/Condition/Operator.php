<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

class Operator
{
	public const OR = 'or';
	public const AND = 'and';

	/**
	 * returns true when operator is valid
	 *
	 * @param string $operator
	 * @return boolean
	 */
	public static function isValid(string $operator): bool
	{
		return in_array($operator, [self::AND, self::OR], true);
	}
}
