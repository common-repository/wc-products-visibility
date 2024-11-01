<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use OneTeamSoftware\Rule\Condition\ConditionFactory as ConditionConditionFactory;
use OneTeamSoftware\Rule\Condition\ConditionFactoryInterface;
use OneTeamSoftware\Rule\Condition\ConditionInterface;

class ConditionFactory
{
	/**
	 * @var ConditionFactoryInterface
	 */
	private $conditionFactory;

	/**
	 * constructor
	 *
	 * @param ConditionFactoryInterface $conditionFactory
	 */
	public function __construct(ConditionFactoryInterface $conditionFactory = null)
	{
		$this->conditionFactory = $conditionFactory ?? new ConditionConditionFactory();
	}

	/**
	 * returns condition for a given definition
	 *
	 * @param ConditionDefinitionInterface $conditionDefinition
	 * @param array $settings
	 * @return ConditionInterface
	 */
	public function create(ConditionDefinitionInterface $conditionDefinition, array $settings): ConditionInterface
	{
		return $this->conditionFactory
				->withMatcherId($conditionDefinition->getMatcherId())
				->withOptionsOperator($conditionDefinition->getOptionsOperator($settings))
				->withOptions($conditionDefinition->getOptions($settings))
				->create();
	}
}
