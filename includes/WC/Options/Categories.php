<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\CacheInterface;

class Categories extends TaxonomyOptions
{
	/**
	 * constructor
	 *
	 * @param string string $textDomain
	 * @param TaxonomyArrayBuilderInterface $taxonomyArrayBuilder
	 * @param CacheInterface $cache
	 */
	public function __construct(
		string $textDomain,
		TaxonomyArrayBuilderInterface $taxonomyArrayBuilder = null,
		CacheInterface $cache = null
	) {
		parent::__construct($textDomain, 'product_cat', $taxonomyArrayBuilder, $cache);
	}
}
