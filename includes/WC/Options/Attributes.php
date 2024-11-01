<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\CacheInterface;

class Attributes extends AbstractCachedOptions
{
	/**
	 * @var Taxonomies
	 */
	private $taxonomies;

	/**
	 * constructor
	 *
	 * @param string $textDomain
	 * @param Taxonomies $taxonomies
	 * @param CacheInterface $cache
	 */
	public function __construct(string $textDomain, Taxonomies $taxonomies, CacheInterface $cache = null)
	{
		parent::__construct($textDomain, $cache);

		$this->taxonomies = $taxonomies;
	}

	/**
	 * load options
	 *
	 * @return array
	 */
	protected function loadOptions(): array
	{
		return array_filter($this->taxonomies->getOptions(), function ($key) {
			return 0 === strpos((string)$key, 'pa_');
		}, ARRAY_FILTER_USE_KEY);
	}
}
