<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule;

use OneTeamSoftware\Rule\Condition\Conditions;
use OneTeamSoftware\Rule\Matcher\MatcherInterface;

interface RuleFactoryInterface
{
	/**
	 * sets rule id and returns itself
	 *
	 * @param string $ruleId
	 * @return RuleFactoryInterface
	 */
	public function withRuleId(string $ruleId): RuleFactoryInterface;

	/**
	 * sets matcher and returns itself
	 *
	 * @param MatcherInterface $matcher
	 * @return RuleFactoryInterface
	 */
	public function withMatcher(MatcherInterface $matcher): RuleFactoryInterface;

	/**
	 * sets conditions and returns itself
	 *
	 * @param Conditions $conditions
	 * @return RuleFactoryInterface
	 */
	public function withConditions(Conditions $conditions): RuleFactoryInterface;

	/**
	 * sets condition operator and returns itself
	 *
	 * @param string $conditionOperator
	 * @return RuleFactoryInterface
	 */
	public function withConditionOperator(string $conditionOperator): RuleFactoryInterface;

	/**
	 * sets result when matched, it can be true or false and returns itself
	 *
	 * @param boolean $resultWhenMatched
	 * @return RuleFactoryInterface
	 */
	public function withResultWhenMatched(bool $resultWhenMatched): RuleFactoryInterface;

	/**
	 * returns rule
	 *
	 * @return RuleInterface
	 */
	public function create(): RuleInterface;
}
