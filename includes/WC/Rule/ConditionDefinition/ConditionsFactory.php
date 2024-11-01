<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\Conditions;

class ConditionsFactory
{
	/**
	 * @var ConditionFactory
	 */
	private $conditionFactory;

	/**
	 * @var ConditionDefinitions
	 */
	private $conditionDefinitions;

	/**
	 * constructor
	 *
	 * @param ConditionFactory $conditionFactory
	 */
	public function __construct(ConditionFactory $conditionFactory = null)
	{
		$this->conditionFactory = $conditionFactory ?? new ConditionFactory();
		$this->conditionDefinitions = new ConditionDefinitions();
	}

	/**
	 * sets condition definitions and returns itself
	 *
	 * @param ConditionDefinitions $conditionDefinitions
	 * @return self
	 */
	public function withConditionDefinitions(ConditionDefinitions $conditionDefinitions): ConditionsFactory
	{
		$this->conditionDefinitions = $conditionDefinitions;
		return $this;
	}

	/**
	 * returns conditions for a given list of definitions
	 *
	 * @param array $settings
	 * @return Conditions
	 */
	public function create(array $settings): Conditions
	{
		$conditions = new Conditions();

		foreach ($this->conditionDefinitions as $conditionDefinition) {
			$conditions->add($this->conditionFactory->create($conditionDefinition, $settings));
		}

		return $conditions;
	}
}
