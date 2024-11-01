<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\ItemsFilter;

interface ItemsFilterInterface
{
	/**
	 * returns filtered items
	 *
	 * @param array $items
	 * @return array
	 */
	public function filter(array $items): array;
}
