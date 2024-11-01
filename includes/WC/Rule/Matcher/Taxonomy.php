<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\Rule\Matcher\ItemMatcher;
use OneTeamSoftware\Rule\Matcher\ItemsMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\Taxonomy as ItemOptionMatcherTaxonomy;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\AttributeMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatcher;
use OneTeamSoftware\WC\Rule\Matcher\ItemOptionMatcher\TaxonomyMatcher\TaxonomyMatchers;

class Taxonomy extends ItemsMatcher
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
				new ItemOptionMatcherTaxonomy(
					$logger,
					(new TaxonomyMatchers())
						->addMatcher(new AttributeMatcher($logger))
						->addMatcher(new TaxonomyMatcher($logger))
				)
			),
			$logger
		);
	}
}
