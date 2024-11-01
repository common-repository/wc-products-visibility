<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Rule\Matcher\ItemMatcher;
use OneTeamSoftware\Rule\Matcher\ItemsMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\OnBackorder as ItemOptionMatcherOnBackorder;

class OnBackorder extends ItemsMatcher
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
				new ItemOptionMatcherOnBackorder($logger)
			),
			$logger
		);
	}
}
