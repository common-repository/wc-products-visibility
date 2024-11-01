<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

use OneTeamSoftware\Cache\Cache;
use OneTeamSoftware\Cache\CacheInterface;
use OneTeamSoftware\WP\Cache\Storage\Transient;

abstract class AbstractCachedOptions implements OptionsInterface
{
	/**
	 * @var string
	 */
	protected $textDomain;

	/**
	 * @var CacheInterface
	 */
	protected $cache;

	/**
	 * constructor
	 *
	 * @param string string $textDomain
	 * @param CacheInterface $cache
	 */
	public function __construct(string $textDomain, CacheInterface $cache = null)
	{
		$this->textDomain = $textDomain;
		$this->cache = $cache ?? new Cache(new Transient());
	}

	/**
	 * returns options
	 *
	 * @return array
	 */
	public function getOptions(): array
	{
		$cacheKey = $this->textDomain . '_' . get_class($this);
		$options = $this->cache->get($cacheKey);
		if (is_array($options)) {
			return $options;
		}

		$options = $this->loadOptions();
		set_transient($cacheKey, $options, DAY_IN_SECONDS);

		return $options;
	}

	/**
	 * loads options
	 *
	 * @return array
	 */
	abstract protected function loadOptions(): array;
}
