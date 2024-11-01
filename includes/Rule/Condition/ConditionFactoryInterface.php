<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

interface ConditionFactoryInterface
{
	/**
	 * sets matcher id
	 *
	 * @param string $matcherId
	 * @return ConditionFactoryInterface
	 */
	public function withMatcherId(string $matcherId): ConditionFactoryInterface;

	/**
	 * set options operator
	 *
	 * @param string $optionsOperator
	 * @return ConditionFactoryInterface
	 */
	public function withOptionsOperator(string $optionsOperator): ConditionFactoryInterface;

	/**
	 * sets items operator
	 *
	 * @param string $itemsOperator
	 * @return ConditionFactoryInterface
	 */
	public function withItemsOperator(string $itemsOperator): ConditionFactoryInterface;

	/**
	 * sets condition options
	 *
	 * @param array $options
	 * @return ConditionFactoryInterface
	 */
	public function withOptions(array $options): ConditionFactoryInterface;

	/**
	 * returns condition from an array
	 *
	 * @return ConditionInterface
	 */
	public function create(): ConditionInterface;
}
