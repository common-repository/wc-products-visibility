<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Matcher;

abstract class AbstractMatcher implements MatcherInterface
{
	/**
	 * @var string
	 */
	protected $matcherId;

	/**
	 * constructor
	 *
	 * @param string $matcherId
	 */
	public function __construct(string $matcherId)
	{
		$this->matcherId = $matcherId;
	}

	/**
	 * returns matcher ID
	 *
	 * @return string
	 */
	public function getMatcherId(): string
	{
		return $this->matcherId;
	}
}
