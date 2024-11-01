<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\WC\Options\OptionsInterface;
use OneTeamSoftware\WC\Options\ProductTypes as OptionsProductTypes;
use OneTeamSoftware\WC\Rule\Matcher\ProductType as MatcherProductType;

class ProductTypes extends AbstractOptionsOrConditionDefinition
{
	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param OptionsInterface $options
	 */
	public function __construct(string $textDomain, OptionsInterface $options = null)
	{
		parent::__construct($textDomain, $options ?? new OptionsProductTypes($textDomain));
	}

	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return MatcherProductType::class;
	}

	/**
	 * returns option key
	 *
	 * @return string
	 */
	protected function getKey(): string
	{
		return 'product_type';
	}

	/**
	 * returns option name
	 *
	 * @return string
	 */
	protected function getName(): string
	{
		return __('Product Types', $this->textDomain);
	}
}
