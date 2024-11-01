<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

interface TaxonomyArrayBuilderInterface
{
	/**
	 * sets name prefix and returns itself
	 *
	 * @param string $namePrefix
	 * @return TaxonomyArrayBuilderInterface
	 */
	public function withNamePrefix(string $namePrefix): TaxonomyArrayBuilderInterface;

	/**
	 * returns a given traxonomy as an array
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public function build(string $taxonomy): array;
}
