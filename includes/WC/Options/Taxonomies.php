<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\CacheInterface;

class Taxonomies extends AbstractCachedOptions
{
	/**
	 * @var TaxonomyArrayBuilderInterface
	 */
	private $taxonomyArrayBuilder;

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
		parent::__construct($textDomain, $cache);

		$this->taxonomyArrayBuilder = $taxonomyArrayBuilder ?? new TaxonomyArrayBuilder();
	}

	/**
	 * load options
	 *
	 * @return array
	 */
	protected function loadOptions(): array
	{
		$allTaxonomiesAsArray = [];
		$taxonomies = get_object_taxonomies('product', 'objects');
		foreach ($taxonomies as $taxonomy) {
			$allTaxonomiesAsArray = array_merge(
				$allTaxonomiesAsArray,
				$this->taxonomyArrayBuilder->withNamePrefix($taxonomy->label . ': ')->build($taxonomy->name)
			);
		}

		return $allTaxonomiesAsArray;
	}
}
