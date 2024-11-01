<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\ColumnTypeBuilder;

use OneTeamSoftware\WP\Admin\Table\ColumnTypeBuilder\ColumnTypeBuilderInterface;

class RuleBuilder implements ColumnTypeBuilderInterface
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * constructor
	 *
	 * @param string $id
	 */
	public function __construct(string $id)
	{
		$this->id = $id;
	}

	/**
	 * returns column type
	 *
	 * @return string
	 */
	public function getColumnType(): string
	{
		return 'rule';
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
		$url = admin_url(
			sprintf(
				'admin.php?page=%s&tab=edit&id=%s',
				$this->id,
				$row['id'] ?? ''
			)
		);

		return sprintf(
			'<a href="%s">%s</a>',
			$url,
			$row['name'] ?? 'Unknown'
		);
	}
}
