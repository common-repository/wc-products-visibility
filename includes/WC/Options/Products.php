<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\CacheInterface;

class Products extends AbstractCachedOptions
{
	/**
	 * constructor
	 *
	 * @param string string $textDomain
	 * @param CacheInterface $cache
	 */
	public function __construct(string $textDomain, CacheInterface $cache = null)
	{
		parent::__construct($textDomain, $cache);
	}

	/**
	 * load options
	 *
	 * @return array
	 */
	protected function loadOptions(): array
	{
		global $wpdb;

		$query = "
			SELECT posts.ID AS product_id, posts.post_title AS product_title
			FROM {$wpdb->posts} AS posts
			WHERE 1=1
				AND posts.post_type IN ('product', 'product_variation')
  				AND posts.post_status = 'publish'
		";

		$rows = $wpdb->get_results($query, ARRAY_A);

		$keyValuePairs = [];
		foreach ($rows as $row) {
			$keyValuePairs[$row['product_id']] = sprintf('%s (ID: %s)', $row['product_title'], $row['product_id']);
		}

		return $keyValuePairs;
	}
}
