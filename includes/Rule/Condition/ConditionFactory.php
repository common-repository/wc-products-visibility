<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

class ConditionFactory implements ConditionFactoryInterface
{
	/**
	 * @var string
	 */
	protected $matcherId;

	/**
	 * @var string
	 */
	protected $optionsOperator;

	/**
	 * @var string
	 */
	protected $itemsOperator;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->matcherId = '';
		$this->optionsOperator = Operator::AND;
		$this->itemsOperator = Operator::AND;
		$this->options = [];
	}

	/**
	 * sets matcher id
	 *
	 * @param string $matcherId
	 * @return ConditionFactoryInterface
	 */
	public function withMatcherId(string $matcherId): ConditionFactoryInterface
	{
		$this->matcherId = $matcherId;
		return $this;
	}

	/**
	 * set options operator
	 *
	 * @param string $optionsOperator
	 * @return ConditionFactoryInterface
	 */
	public function withOptionsOperator(string $optionsOperator): ConditionFactoryInterface
	{
		if (Operator::isValid($optionsOperator)) {
			$this->optionsOperator = $optionsOperator;
		}
		return $this;
	}

	/**
	 * sets items operator
	 *
	 * @param string $itemsOperator
	 * @return ConditionFactoryInterface
	 */
	public function withItemsOperator(string $itemsOperator): ConditionFactoryInterface
	{
		if (Operator::isValid($itemsOperator)) {
			$this->itemsOperator = $itemsOperator;
		}
		return $this;
	}

	/**
	 * sets condition options
	 *
	 * @param array $options
	 * @return ConditionFactoryInterface
	 */
	public function withOptions(array $options): ConditionFactoryInterface
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * returns condition from an array
	 *
	 * @return ConditionInterface
	 */
	public function create(): ConditionInterface
	{
		return new Condition(
			$this->matcherId,
			$this->optionsOperator,
			$this->itemsOperator,
			$this->options
		);
	}
}
