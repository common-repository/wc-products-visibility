<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\ConditionInterface;
use OneTeamSoftware\Rule\Condition\IsConditionMatched;
use OneTeamSoftware\Rule\Condition\Operator;

class ItemsMatcher extends AbstractMatcher
{
	/**
	 * @var ItemMatcherInterface $itemMatcher
	 */
	protected $itemMatcher;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * constructor
	 *
	 * @param string $matcherId
	 * @param ItemMatcherInterface $itemMatcher
	 * @param LoggerInterface $logger
	 */
	public function __construct(string $matcherId, ItemMatcherInterface $itemMatcher, LoggerInterface $logger = null)
	{
		parent::__construct($matcherId);

		$this->itemMatcher = $itemMatcher;
		$this->logger = $logger ?? new NullLogger();
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
		if ($this->matcherId !== $condition->getMatcherId()) {
			$this->logger->debug(__FILE__, __LINE__, 'Matcher ID %s does not match condition matcher ID %s, return as a match', $this->matcherId, $condition->getMatcherId()); // phpcs:ignore

			return true;
		}

		$this->logger->debug(__FILE__, __LINE__, 'Match %d items by matcher %s with options %s', count($items), $condition->getMatcherId(), json_encode($condition->getOptions())); // phpcs:ignore

		$numberOfMatches = 0;
		$operator = $condition->getItemsOperator();

		foreach ($items as $item) {
			if ($this->itemMatcher->matchItem($item, $condition)) {
				$numberOfMatches++;

				if (Operator::OR === $operator) {
					break;
				}
			} elseif (Operator::AND === $operator) {
				break;
			}
		}

		$this->logger->debug(__FILE__, __LINE__, 'Number of matches by matcher %s is %d', $condition->getMatcherId(), $numberOfMatches); // phpcs:ignore

		return IsConditionMatched::isMatched($operator, count($items), $numberOfMatches);
	}
}
