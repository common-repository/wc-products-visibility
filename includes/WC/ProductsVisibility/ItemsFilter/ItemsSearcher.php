<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\ItemsFilter;

class ItemsSearcher implements ItemsFilterInterface
{
	/**
	 * @var string
	 */
	private $search;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->search = '';
	}

	/**
	 * sets search and returns itself
	 *
	 * @param string $search
	 * @return ItemsSearcher
	 */
	public function withSearch(string $search): ItemsSearcher
	{
		$this->search = $search;

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
		return array_filter(
			$items,
			function ($item) {
				if (empty($item['id'])) {
					return false;
				}

				if (empty($this->search)) {
					return true;
				}

				return false !== strpos(strtolower($item['name'] ?? ''), $this->search);
			}
		);
	}
}
