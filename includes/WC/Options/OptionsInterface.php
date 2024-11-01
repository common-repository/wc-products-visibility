<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

interface OptionsInterface
{
	/**
	 * returns options
	 *
	 * @return array
	 */
	public function getOptions(): array;
}
