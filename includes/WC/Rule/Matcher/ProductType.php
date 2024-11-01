<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Rule\Matcher;

use OneTeamSoftware\Logger\LoggerInterface;

class ProductType extends SingleTaxonomy
{
	/**
	 * constructor
	 *
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger = null)
	{
		parent::__construct('product_type', $logger);
	}
}
