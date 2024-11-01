<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

use DateTime;
use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Condition\ConditionInterface;

class FromDate implements MatcherInterface
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var DateTime
	 */
	private $dateTimeNow;

	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 * @param DateTime $dateTimeNow
	 */
	public function __construct(LoggerInterface $logger = null, DateTime $dateTimeNow = null)
	{
		$this->logger = $logger ?? new NullLogger();
		$this->dateTimeNow = $dateTimeNow ?? new DateTime('now');
	}

	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return get_class($this);
	}

	/**
	 * returns true when from date condition is not set or matches
	 *
	 * @param array $items
	 * @param ConditionInterface $condition
	 * @return boolean
	 */
	public function match(array $items, ConditionInterface $condition): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'Try to match from date');

		$options = $condition->getOptions();

		$fromDate = $options['from_date'] ?? '';
		if (false === empty($fromDate) && strtotime($this->dateTimeNow->format('Y-m-d')) < strtotime($fromDate)) {
			$this->logger->debug(__FILE__, __LINE__, 'Date is before the from date, hence condition did not match');
			return false;
		}

		$this->logger->debug(__FILE__, __LINE__, 'We can consider from date as matched');

		return true;
	}
}
