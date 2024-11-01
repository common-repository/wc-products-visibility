<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

interface ItemOptionMatcherInterface
{
	/**
	 * returns true when items match a given option
	 *
	 * @param array $item
	 * @param mixed $option
	 * @return boolean
	 */
	public function matchItemOption(array $item, $option): bool;
}
