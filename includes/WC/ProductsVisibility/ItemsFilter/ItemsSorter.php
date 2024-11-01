<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\ItemsFilter;

class ItemsSorter implements ItemsFilterInterface
{
	/**
	 * @var string
	 */
	public const ORDER_DIRECTION_ASC = 'asc';

	/**
	 * @var string
	 */
	public const ORDER_DIRECTION_DESC = 'desc';

	/**
	 * @var string
	 */
	private $orderBy;

	/**
	 * @var string
	 */
	private $orderDirection;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->orderBy = '';
		$this->orderDirection = self::ORDER_DIRECTION_ASC;
	}

	/**
	 * sets a property key items should be sorted by
	 *
	 * @param string $orderBy
	 * @return ItemsSorter
	 */
	public function withOrderBy(string $orderBy): ItemsSorter
	{
		$this->orderBy = $orderBy;

		return $this;
	}

	/**
	 * sets an order direction
	 *
	 * @param string $orderDirection
	 * @return ItemsSorter
	 */
	public function withOrderDirection(string $orderDirection): ItemsSorter
	{
		if (in_array($this->orderDirection, [self::ORDER_DIRECTION_ASC, self::ORDER_DIRECTION_DESC], true)) {
			$this->orderDirection = $orderDirection;
		}

		return $this;
	}

	/**
	 * returns filtered items
	 *
	 * @param array $items
	 * @return array
	 */
	public function filter(array $items): array
	{
		if (empty($this->orderBy)) {
			return $items;
		}

		uasort($items, function ($item1, $item2) {
			if (false === is_array($item1) || false === is_array($item2)) {
				return 0;
			}

			if (false === isset($item1[$this->orderBy]) || false === isset($item2[$this->orderBy])) {
				return 0;
			}

			if ($this->orderDirection === self::ORDER_DIRECTION_DESC) {
				return $item1[$this->orderBy] > $item2[$this->orderBy] ? -1 : 1;
			}

			return $item1[$this->orderBy] > $item2[$this->orderBy] ? 1 : -1;
		});

		return $items;
	}
}
