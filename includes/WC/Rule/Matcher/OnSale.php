<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Rule\Matcher\ItemMatcher;
use OneTeamSoftware\Rule\Matcher\ItemsMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\OnSale as ItemOptionMatcherOnSale;

class OnSale extends ItemsMatcher
{
	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger = null)
	{
		parent::__construct(
			get_class($this),
			new ItemMatcher(
				new ItemOptionMatcherOnSale($logger)
			),
			$logger
		);
	}
}
