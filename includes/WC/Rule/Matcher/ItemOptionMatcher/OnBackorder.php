<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Logger\NullLogger;
use OneTeamSoftware\Rule\Matcher\ItemOptionMatcherInterface;
use WC_Product;

class OnBackorder implements ItemOptionMatcherInterface
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger = null)
	{
		$this->logger = $logger ?? new NullLogger();
	}

	/**
	 * returns true when items match a given option
	 *
	 * @param array $item
	 * @param mixed $isOnBackorder
	 * @return boolean
	 */
	public function matchItemOption(array $item, $isOnBackorder): bool
	{
		if (empty($isOnBackorder) || empty($item['data']) || false === ($item['data'] instanceof WC_Product)) {
			$this->logger->debug(__FILE__, __LINE__, 'No need to match on backorder');
			return true;
		}

		$product = $item['data'];

		$this->logger->debug(__FILE__, __LINE__, 'Try to match if product %d is on backorder', $product->get_id()); //phpcs:ignore

		$isMatched = $product->is_on_backorder();

		$this->logger->debug(__FILE__, __LINE__, 'Product ID: %d has %s matched', $product->get_id(), $isMatched ? '' : 'not');

		return $isMatched;
	}
}
