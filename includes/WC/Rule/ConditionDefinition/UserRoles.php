<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\WC\Options\OptionsInterface;
use OneTeamSoftware\WC\Options\UserRoles as OptionsUserRoles;
use OneTeamSoftware\WC\Rule\Matcher\UserRole as MatcherUserRole;

class UserRoles extends AbstractOptionsOrConditionDefinition
{
	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param OptionsInterface $options
	 */
	public function __construct(string $textDomain, OptionsInterface $options = null)
	{
		parent::__construct($textDomain, $options ?? new OptionsUserRoles($textDomain));
	}

	/**
	 * returns condition's matcher
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return MatcherUserRole::class;
	}

	/**
	 * returns option key
	 *
	 * @return string
	 */
	protected function getKey(): string
	{
		return 'userRoles';
	}

	/**
	 * returns option name
	 *
	 * @return string
	 */
	protected function getName(): string
	{
		return __('User Roles', $this->textDomain);
	}
}
