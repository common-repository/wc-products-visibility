<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\ColumnTypeBuilder;

use OneTeamSoftware\WP\Admin\Table\ColumnTypeBuilder\ColumnTypeBuilderInterface;

class EnabledBuilder implements ColumnTypeBuilderInterface
{
	/**
	 * returns column type
	 *
	 * @return string
	 */
	public function getColumnType(): string
	{
		return 'enabled';
	}

	/**
	 * builds and returns contents for the given row and column
	 *
	 * @param array $row
	 * @param string $columnName
	 * @return string
	 */
	public function build(array $row, string $columnName): string
	{
		return sprintf(
			'<input type="checkbox" data-name="enabled" value="%s"%s/>',
			$row['id'] ?? '',
			empty($row[$columnName]) ? '' : 'checked="checked"'
		);
	}
}
