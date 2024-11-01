<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Rule\Matcher\MatcherInterface;
use OneTeamSoftware\Rule\Rule;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitions;

interface RuleFactoryInterface
{
	/**
	 * sets logger and returns itself
	 *
	 * @param LoggerInterface $logger
	 * @return RuleFactoryInterface
	 */
	public function withLogger(LoggerInterface $logger): RuleFactoryInterface;

	/**
	 * sets condition definitions and returns itself
	 *
	 * @param ConditionDefinitions $conditionDefinitions
	 * @return RuleFactoryInterface
	 */
	public function withConditionDefinitions(ConditionDefinitions $conditionDefinitions): RuleFactoryInterface;

	/**
	 * sets matcher and returns itself
	 *
	 * @param MatcherInterface $matcher
	 * @return RuleFactoryInterface
	 */
	public function withMatcher(MatcherInterface $matcher): RuleFactoryInterface;

	/**
	 * returns rule for the given rule settings
	 *
	 * @param array $ruleSettings
	 * @return Rule
	 */
	public function create(array $ruleSettings): Rule;
}
