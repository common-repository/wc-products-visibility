<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Rule\Condition\ConditionInterface;

class Matchers implements MatcherInterface
{
	/**
	 * @var array<MatcherInterface>
	 */
	protected $matchers;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->matchers = [];
	}

	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return 'matchers';
	}

	/**
	 * adds matcher
	 *
	 * @param MatcherInterface $matcher
	 * @return self
	 */
	public function add(MatcherInterface $matcher): Matchers
	{
		$this->matchers[$matcher->getMatcherId()] = $matcher;
		$this->matchers[get_class($matcher)] = $matcher;

		return $this;
	}

	/**
	 * returns true when a given matcher registered
	 *
	 * @param string $matcherId
	 * @return bool
	 */
	public function has(string $matcherId): bool
	{
		return isset($this->matchers[$matcherId]);
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
		if (false === isset($this->matchers[$condition->getMatcherId()])) {
			return true;
		}

		$matcher = &$this->matchers[$condition->getMatcherId()];

		return $matcher->match($items, $condition);
	}
}
