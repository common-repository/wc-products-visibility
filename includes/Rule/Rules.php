<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule;

class Rules
{
	/**
	 * @var array<Rule>
	 */
	protected $rules;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->rules = [];
	}

	/**
	 * adds rule
	 *
	 * @param RuleInterface $rule
	 * @return void
	 */
	public function add(RuleInterface $rule): void
	{
		$this->rules[$rule->getRuleId()] = $rule;
	}

	/**
	 * matches items against rules
	 *
	 * @param array $items
	 * @return array
	 */
	public function match(array $items): array
	{
		$matches = [];
		foreach ($this->rules as $rule) {
			$matches[$rule->getRuleId()] = $rule->match($items);
		}

		return $matches;
	}
}
