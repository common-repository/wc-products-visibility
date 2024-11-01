<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\WC\Options\OptionsInterface;
use OneTeamSoftware\WC\Options\Taxonomies as OptionsTaxonomies;
use OneTeamSoftware\WC\Rule\Matcher\Taxonomy as MatcherTaxonomy;

class Taxonomies extends AbstractOptionsConditionDefinition
{
	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param OptionsInterface $options
	 */
	public function __construct(string $textDomain, OptionsInterface $options = null)
	{
		parent::__construct($textDomain, $options ?? new OptionsTaxonomies($textDomain));
	}

	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return MatcherTaxonomy::class;
	}

	/**
	 * returns option key
	 *
	 * @return string
	 */
	protected function getKey(): string
	{
		return 'Taxonomy';
	}

	/**
	 * returns option name
	 *
	 * @return string
	 */
	protected function getName(): string
	{
		return __('Taxonomies', $this->textDomain);
	}
}
