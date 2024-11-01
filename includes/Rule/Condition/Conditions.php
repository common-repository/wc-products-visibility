<?php

declare(strict_types=1);

namespace OneTeamSoftware\Rule\Condition;

use Iterator;

class Conditions implements Iterator
{
	/**
	 * @var integer
	 */
	protected $index;

	/**
	 * @var array<Condition>
	 */
	protected $conditions;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->index = 0;
		$this->conditions = [];
	}

	/**
	 * adds condition
	 *
	 * @param ConditionInterface $condition
	 * @return void
	 */
	public function add(ConditionInterface $condition): void
	{
		$this->conditions[] = $condition;
	}

	/**
	 * returns count of conditions
	 *
	 * @return integer
	 */
	public function count(): int
	{
		return count($this->conditions);
	}

	/**
	 * returns current condition
	 *
	 * @return ConditionInterface
	 */
	public function current(): ?ConditionInterface
	{
		return $this->conditions[$this->index] ?? null;
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
		return isset($this->conditions[$this->index]);
	}
}
