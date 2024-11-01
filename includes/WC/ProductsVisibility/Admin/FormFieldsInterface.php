<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

interface FormFieldsInterface
{
	/**
	 * returns form fields
	 *
	 * @return array
	 */
	public function getFields(): array;
}
