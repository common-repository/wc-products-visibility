<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule;

interface RuleInterface
{
	/**
	 * returns rule id
	 *
	 * @return string
	 */
	public function getRuleId(): string;

	/**
	 * matches items against a given collection of conditions
	 *
	 * @param array $items
	 * @return boolean
	 */
	public function match(array $items): bool;
}
