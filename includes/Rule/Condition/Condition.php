<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

class Condition implements ConditionInterface
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
	 *
	 * @param string $matcherId
	 * @param string $optionsOperator
	 * @param string $itemsOperator
	 * @param array $options
	 */
	public function __construct(
		string $matcherId = '',
		string $optionsOperator = Operator::AND,
		string $itemsOperator = Operator::AND,
		array $options = []
	) {
		$this->matcherId = $matcherId;
		$this->optionsOperator = Operator::isValid($optionsOperator) ? $optionsOperator : Operator::AND;
		$this->itemsOperator = Operator::isValid($itemsOperator) ? $itemsOperator : Operator::AND;
		$this->options = $options;
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

	/**
	 * returns options operator
	 *
	 * @return string
	 */
	public function getOptionsOperator(): string
	{
		return $this->optionsOperator;
	}

	/**
	 * returns items operator
	 *
	 * @return string
	 */
	public function getItemsOperator(): string
	{
		return $this->itemsOperator;
	}

	/**
	 * returns options
	 *
	 * @return string
	 */
	public function getOptions(): array
	{
		return $this->options;
	}
}
