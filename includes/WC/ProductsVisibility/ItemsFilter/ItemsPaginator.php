<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\ItemsFilter;

class ItemsPaginator implements ItemsFilterInterface
{
	/**
	 * @var int
	 */
	private $page;

	/**
	 * @var int
	 */
	private $limit;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->page = 1;
		$this->limit = 20;
	}

	/**
	 * sets page and returns itself
	 *
	 * @param int $page
	 * @return ItemsPaginator
	 */
	public function withPage(int $page): ItemsPaginator
	{
		if ($page <= 0) {
			return $this;
		}

		$this->page = $page;

		return $this;
	}

	/**
	 * sets limit and returns itself
	 *
	 * @param int $limit
	 * @return ItemsPaginator
	 */
	public function withLimit(int $limit): ItemsPaginator
	{
		if ($limit <= 0) {
			return $this;
		}

		$this->limit = $limit;

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
		$offset = ($this->page - 1) * $this->limit;

		return array_slice($items, $offset, $this->limit);
	}
}
