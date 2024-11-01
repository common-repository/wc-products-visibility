<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\WC\Options\OptionsInterface;
use OneTeamSoftware\WC\Options\ShippingClasses as OptionsShippingClasses;
use OneTeamSoftware\WC\Rule\Matcher\ShippingClass as MatcherShippingClass;

class ShippingClasses extends AbstractOptionsOrConditionDefinition
{
	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param OptionsInterface $options
	 */
	public function __construct(string $textDomain, OptionsInterface $options = null)
	{
		parent::__construct($textDomain, $options ?? new OptionsShippingClasses($textDomain));
	}

	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return MatcherShippingClass::class;
	}

	/**
	 * returns option key
	 *
	 * @return string
	 */
	protected function getKey(): string
	{
		return 'shipping_class';
	}

	/**
	 * returns option name
	 *
	 * @return string
	 */
	protected function getName(): string
	{
		return __('Shipping Classes', $this->textDomain);
	}
}
