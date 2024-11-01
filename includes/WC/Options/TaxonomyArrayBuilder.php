<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

class TaxonomyArrayBuilder implements TaxonomyArrayBuilderInterface
{
	/**
	 * @var string
	 */
	private $namePrefix;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->namePrefix = '';
	}

	/**
	 * sets name prefix and returns itself
	 *
	 * @param string $namePrefix
	 * @return TaxonomyArrayBuilderInterface
	 */
	public function withNamePrefix(string $namePrefix): TaxonomyArrayBuilderInterface
	{
		$this->namePrefix = $namePrefix;

		return $this;
	}

	/**
	 * returns a given traxonomy as an array
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public function build(string $taxonomy): array
	{
		$taxArray = [];

		$terms = get_terms(['taxonomy' => $taxonomy]);
		foreach ($terms as $term) {
			if (is_object($term) && property_exists($term, 'term_id') && property_exists($term, 'name')) {
				$taxArray[$taxonomy . '|' . $term->slug] = $this->namePrefix . $term->name;
			}
		}

		return $taxArray;
	}
}
