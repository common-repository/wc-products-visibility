<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\Conditions;
use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\Rule\Matcher\FalseMatcher;
use OneTeamSoftware\Rule\Matcher\MatcherInterface;

class RuleFactory implements RuleFactoryInterface
{
	/**
	 * @var string
	 */
	protected $ruleId;

	/**
	 * @var MatcherInterface
	 */
	protected $matcher;

	/**
	 * @var Conditions
	 */
	protected $conditions;

	/**
	 * @var string
	 */
	protected $conditionOperator;

	/**
	 * @var boolean
	 */
	protected $resultWhenMatched;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->ruleId = '';
		$this->matcher = new FalseMatcher();
		$this->conditions = new Conditions();
		$this->conditionOperator = Operator::AND;
		$this->resultWhenMatched = true;
		$this->logger = new NullLogger();
	}

	/**
	 * sets rule id and returns itself
	 *
	 * @param string $ruleId
	 * @return RuleFactoryInterface
	 */
	public function withRuleId(string $ruleId): RuleFactoryInterface
	{
		$this->ruleId = $ruleId;

		return $this;
	}

	/**
	 * sets matcher and returns itself
	 *
	 * @param MatcherInterface $matcher
	 * @return RuleFactoryInterface
	 */
	public function withMatcher(MatcherInterface $matcher): RuleFactoryInterface
	{
		$this->matcher = $matcher;

		return $this;
	}

	/**
	 * sets conditions and returns itself
	 *
	 * @param Conditions $conditions
	 * @return RuleFactoryInterface
	 */
	public function withConditions(Conditions $conditions): RuleFactoryInterface
	{
		$this->conditions = $conditions;

		return $this;
	}

	/**
	 * sets condition operator and returns itself
	 *
	 * @param string $conditionOperator
	 * @return RuleFactoryInterface
	 */
	public function withConditionOperator(string $conditionOperator): RuleFactoryInterface
	{
		if (Operator::isValid($conditionOperator)) {
			$this->conditionOperator = $conditionOperator;
		}

		return $this;
	}

	/**
	 * sets result when matched, it can be true or false and returns itself
	 *
	 * @param boolean $resultWhenMatched
	 * @return RuleFactoryInterface
	 */
	public function withResultWhenMatched(bool $resultWhenMatched): RuleFactoryInterface
	{
		$this->resultWhenMatched = $resultWhenMatched;

		return $this;
	}

	/**
	 * sets a logger and returns itself
	 *
	 * @param LoggerInterface $logger
	 * @return RuleFactoryInterface
	 */
	public function withLogger(LoggerInterface $logger): RuleFactoryInterface
	{
		$this->logger = $logger;

		return $this;
	}

	/**
	 * returns rule
	 *
	 * @return RuleInterface
	 */
	public function create(): RuleInterface
	{
		return new Rule(
			$this->ruleId,
			$this->matcher,
			$this->conditions,
			$this->conditionOperator,
			$this->resultWhenMatched,
			$this->logger
		);
	}
}
