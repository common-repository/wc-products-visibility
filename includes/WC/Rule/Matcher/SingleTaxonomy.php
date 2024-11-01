<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Rule\Matcher\ItemMatcher;
use OneTeamSoftware\Rule\Matcher\ItemsMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\Taxonomy as ItemOptionMatcherTaxonomy;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatcher;

class SingleTaxonomy extends ItemsMatcher
{
	/**
	 * constructor
	 *
	 * @param string $taxonomy
	 * @param LoggerInterface $logger
	 */
	public function __construct(string $taxonomy, LoggerInterface $logger = null)
	{
		parent::__construct(
			get_class($this),
			new ItemMatcher(
				new ItemOptionMatcherTaxonomy(
					$logger,
					new TaxonomyMatcher($logger, [$taxonomy])
				)
			),
			$logger
		);
	}
}
