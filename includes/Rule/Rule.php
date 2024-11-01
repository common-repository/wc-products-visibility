<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\Conditions;
use OneTeamSoftware\Rule\Condition\IsConditionMatched;
use OneTeamSoftware\Rule\Condition\Operator;
use OneTeamSoftware\Rule\Matcher\MatcherInterface;

class Rule implements RuleInterface
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
	 *
	 * @param string $ruleId
	 * @param MatcherInterface $matcher
	 * @param Conditions $conditions
	 * @param string $conditionOperator
	 * @param bool $resultWhenMatched
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		string $ruleId,
		MatcherInterface $matcher,
		Conditions $conditions,
		string $conditionOperator,
		bool $resultWhenMatched = true,
		LoggerInterface $logger = null
	) {
		$this->ruleId = $ruleId;
		$this->matcher = $matcher;
		$this->conditions = $conditions;
		$this->conditionOperator = Operator::isValid($conditionOperator) ? $conditionOperator : Operator::AND;
		$this->resultWhenMatched = $resultWhenMatched;
		$this->logger = $logger ?? new NullLogger();
	}

	/**
	 * returns rule id
	 *
	 * @return string
	 */
	public function getRuleId(): string
	{
		return $this->ruleId;
	}

	/**
	 * matches items against a given collection of conditions
	 *
	 * @param array $items
	 * @return boolean
	 */
	public function match(array $items): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'match, number of items: %d, result when matched: %s, condition operator: %s', count($items), $this->resultWhenMatched, $this->conditionOperator); // phpcs:ignore

		$numberOfMatches = 0;
		foreach ($this->conditions as $condition) {
			if ($this->matcher->match($items, $condition)) {
				$numberOfMatches++;

				if (Operator::OR === $this->conditionOperator) {
					break;
				}
			} elseif (Operator::AND === $this->conditionOperator) {
				break;
			}
		}

		$result = !$this->resultWhenMatched;

		if (
			IsConditionMatched::isMatched(
				$this->conditionOperator,
				$this->conditions->count(),
				$numberOfMatches
			)
		) {
			$result = $this->resultWhenMatched;
		}

		$this->logger->debug(__FILE__, __LINE__, 'Number of matches %d of %d conditions with result %d', $numberOfMatches, $this->conditions->count(), $result); // phpcs:ignore

		return $result;
	}
}
