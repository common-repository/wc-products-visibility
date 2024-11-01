<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

class AndItemOptionMatcher implements ItemOptionMatcherInterface
{
	/**
	 * returns true when item match a given option
	 *
	 * @param array $item
	 * @param mixed $option
	 * @return boolean
	 */
	public function matchItemOption(array $item, $option): bool
	{
		return !empty($item['value']) && !empty($option);
	}
}
