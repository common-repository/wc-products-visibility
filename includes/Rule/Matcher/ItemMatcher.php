<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\ConditionInterface;
use OneTeamSoftware\Rule\Condition\IsConditionMatched;
use OneTeamSoftware\Rule\Condition\Operator;

class ItemMatcher implements ItemMatcherInterface
{
	/**
	 * @var ItemOptionMatcherInterface $itemMatcher
	 */
	protected $itemOptionMatcher;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * constructor
	 *
	 * @param ItemOptionMatcherInterface $itemMatcher
	 * @param LoggerInterface $logger
	 */
	public function __construct(ItemOptionMatcherInterface $itemMatcher, LoggerInterface $logger = null)
	{
		$this->itemOptionMatcher = $itemMatcher;
		$this->logger = $logger ?? new NullLogger();
	}

	/**
	 * returns true when items match a given condition
	 *
	 * @param array $item
	 * @param ConditionInterface $condition
	 * @return boolean
	 */
	public function matchItem(array $item, ConditionInterface $condition): bool
	{
		$numberOfMatches = 0;
		$operator = $condition->getOptionsOperator();
		/**
		 * @var array
		 */
		$options = $condition->getOptions();

		$this->logger->debug(__FILE__, __LINE__, 'Match an item by matcher %s with options %s', $condition->getMatcherId(), json_encode($condition->getOptions())); // phpcs:ignore

		foreach ($options as $option) {
			if ($this->itemOptionMatcher->matchItemOption($item, $option)) {
				$numberOfMatches++;

				if (Operator::OR === $operator) {
					break;
				}
			} elseif (Operator::AND === $operator) {
				break;
			}
		}

		$this->logger->debug(__FILE__, __LINE__, 'Number of matches by matcher %s is %d', $condition->getMatcherId(), $numberOfMatches); // phpcs:ignore

		return IsConditionMatched::isMatched($operator, count($options), $numberOfMatches);
	}
}
