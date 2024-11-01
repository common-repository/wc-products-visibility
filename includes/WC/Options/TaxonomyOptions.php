<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\CacheInterface;

class TaxonomyOptions extends AbstractCachedOptions
{
	/**
	 * @var string
	 */
	private $taxonomy;

	/**
	 * @var TaxonomyArrayBuilderInterface
	 */
	private $taxonomyArrayBuilder;

	/**
	 * constructor
	 *
	 * @param string string $textDomain
	 * @param string $taxonomy
	 * @param TaxonomyArrayBuilderInterface $taxonomyArrayBuilder
	 * @param CacheInterface $cache
	 */
	public function __construct(
		string $textDomain,
		string $taxonomy,
		TaxonomyArrayBuilderInterface $taxonomyArrayBuilder = null,
		CacheInterface $cache = null
	) {
		parent::__construct($textDomain, $cache);

		$this->taxonomy = $taxonomy;
		$this->taxonomyArrayBuilder = $taxonomyArrayBuilder ?? new TaxonomyArrayBuilder();
	}

	/**
	 * load options
	 *
	 * @return array
	 */
	protected function loadOptions(): array
	{
		return $this->taxonomyArrayBuilder
			->withNamePrefix('')
			->build($this->taxonomy);
	}
}
