<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\ConditionDefinition;

use Iterator;

class ConditionDefinitions implements Iterator
{
	/**
	 * @var integer
	 */
	protected $index;

	/**
	 * @var array<ConditionDefinitionInterface>
	 */
	protected $conditionDefinitions;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->index = 0;
		$this->conditionDefinitions = [];
	}

	/**
	 * adds condition
	 *
	 * @param ConditionDefinitionInterface $condition
	 * @return void
	 */
	public function add(ConditionDefinitionInterface $condition): void
	{
		$this->conditionDefinitions[] = $condition;
	}

	/**
	 * returns count of conditionDefinitions
	 *
	 * @return integer
	 */
	public function count(): int
	{
		return count($this->conditionDefinitions);
	}

	/**
	 * returns current condition
	 *
	 * @return ConditionDefinitionInterface
	 */
	public function current(): ?ConditionDefinitionInterface
	{
		return $this->conditionDefinitions[$this->index] ?? null;
	}

	/**
	 * returns current key
	 *
	 * @return integer
	 */
	public function key(): int
	{
		return $this->index;
	}

	/**
	 * interates to next condition
	 *
	 * @return void
	 */
	public function next(): void
	{
		$this->index++;
	}

	/**
	 * rewinds collection
	 *
	 * @return void
	 */
	public function rewind(): void
	{
		$this->index = 0;
	}

	/**
	 * returns true when position is valid
	 *
	 * @return boolean
	 */
	public function valid(): bool
	{
		return isset($this->conditionDefinitions[$this->index]);
	}
}
