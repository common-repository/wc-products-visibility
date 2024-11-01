<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\Rule\Matcher\MatcherInterface;
use OneTeamSoftware\Rule\Rule;
use OneTeamSoftware\Rule\RuleFactory as RuleRuleFactory;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitions;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionsFactory;

class RuleFactory implements RuleFactoryInterface
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var ConditionsFactory
	 */
	private $conditionsFactory;

	/**
	 * @var RuleRuleFactory
	 */
	private $ruleFactory;

	/**
	 * constructor
	 *
	 * @param ConditionsFactory $conditionsFactory
	 * @param RuleRuleFactory $ruleFactory
	 */
	public function __construct(ConditionsFactory $conditionsFactory = null, RuleRuleFactory $ruleFactory = null)
	{
		$this->conditionsFactory = $conditionsFactory ?? new ConditionsFactory();
		$this->ruleFactory = $ruleFactory ?? new RuleRuleFactory();
		$this->logger = new NullLogger();
	}

	/**
	 * sets logger and returns itself
	 *
	 * @param LoggerInterface $logger
	 * @return self
	 */
	public function withLogger(LoggerInterface $logger): RuleFactoryInterface
	{
		$this->logger = $logger;

		return $this;
	}

	/**
	 * sets condition definitions and returns itself
	 *
	 * @param ConditionDefinitions $conditionDefinitions
	 * @return self
	 */
	public function withConditionDefinitions(ConditionDefinitions $conditionDefinitions): RuleFactoryInterface
	{
		$this->conditionsFactory->withConditionDefinitions($conditionDefinitions);

		return $this;
	}

	/**
	 * sets matcher and returns itself
	 *
	 * @param MatcherInterface $matcher
	 * @return self
	 */
	public function withMatcher(MatcherInterface $matcher): RuleFactoryInterface
	{
		$this->ruleFactory->withMatcher($matcher);

		return $this;
	}

	/**
	 * returns rule for the given rule settings
	 *
	 * @param array $ruleSettings
	 * @return Rule
	 */
	public function create(array $ruleSettings): Rule
	{
		return $this->ruleFactory
			->withConditionOperator(Operator::AND)
			->withConditions($this->conditionsFactory->create($ruleSettings))
			->withLogger($this->logger)
			->create();
	}
}
