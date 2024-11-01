<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\BulkActionHandler\DeleteHandler;
use OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\BulkActionHandler\UpdateEnabledHandler;
use OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\ColumnTypeBuilder\EnabledBuilder;
use OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\ColumnTypeBuilder\RuleBuilder;
use OneTeamSoftware\WC\ProductsVisibility\ItemsFilter\ItemsPaginator;
use OneTeamSoftware\WC\ProductsVisibility\ItemsFilter\ItemsSearcher;
use OneTeamSoftware\WC\ProductsVisibility\ItemsFilter\ItemsSorter;
use OneTeamSoftware\WP\Admin\Notices\Notices;
use OneTeamSoftware\WP\Admin\Table\AbstractTable;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;

class RulesTable extends AbstractTable
{
	/**
	 * @var string
	 */
	protected $pluginPath;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var ItemsStorage
	 */
	protected $itemsStorage;

	/**
	 * @var ItemsSearcher
	 */
	protected $itemsSearcher;

	/**
	 * @var ItemsSorter
	 */
	protected $itemsSorter;

	/**
	 * @var ItemsPaginator
	 */
	protected $itemsPaginator;

	/**
	 * @var Notices
	 */
	protected $notices;

	/**
	 * @var int
	 */
	protected $totalNumberOfItems;

	/**
	 * Constructor
	 *
	 * @param string $id
	 * @param string $pluginPath
	 * @param string $version
	 * @param LoggerInterface $logger
	 * @param ItemsStorage $itemsStorage
	 * @param ItemsSearcher $itemsSearcher
	 * @param ItemsSorter $itemsSorter
	 * @param ItemsPaginator $itemsPaginator
	 */
	public function __construct(
		string $id,
		string $pluginPath,
		string $version,
		LoggerInterface $logger,
		ItemsStorage $itemsStorage,
		ItemsSearcher $itemsSearcher = null,
		ItemsSorter $itemsSorter = null,
		ItemsPaginator $itemsPaginator = null
	) {
		parent::__construct(
			$id,
			[
				'singular' => __('Product', $id),
				'plural' => __('Products', $id),
				'ajax' => false,
				'screen' => $id . '-rules',
			],
			'manage_woocommerce'
		);

		$this->pluginPath = $pluginPath;
		$this->version = $version;
		$this->logger = $logger;
		$this->itemsStorage = $itemsStorage;
		$this->itemsSearcher = $itemsSearcher ?? new ItemsSearcher();
		$this->itemsSorter = $itemsSorter ?? new ItemsSorter();
		$this->itemsPaginator = $itemsPaginator ?? new ItemsPaginator();

		$this->notices = new Notices($id . '-rules-notices');
		$this->totalNumberOfItems = 0;

		$this->addColumnTypeBuilder(new EnabledBuilder());
		$this->addColumnTypeBuilder(new RuleBuilder($this->id));

		$this->addBulkActions();
	}

	/**
	 * adds bulk actions
	 *
	 * @return void
	 */
	protected function addBulkActions(): void
	{
		$this->addBulkAction(
			$this->id . '-action-enable',
			__('Enable', $this->id),
			new UpdateEnabledHandler($this->id, $this->logger, $this->notices, $this->itemsStorage, true)
		);
		$this->addBulkAction(
			$this->id . '-action-disable',
			__('Disable', $this->id),
			new UpdateEnabledHandler($this->id, $this->logger, $this->notices, $this->itemsStorage, false)
		);
		$this->addBulkAction(
			$this->id . '-action-delete',
			__('Delete', $this->id),
			new DeleteHandler($this->id, $this->logger, $this->notices, $this->itemsStorage)
		);
	}

	/**
	 * Returns text used in search button
	 *
	 * @return string
	 */
	protected function getSearchBoxButtonText(): string
	{
		return 'Search';
	}

	/**
	 * Returns column name that is used as primary key
	 *
	 * @return string
	 */
	protected function getPrimaryKey(): string
	{
		return 'id';
	}

	/**
	 * Returns definition of table columns
	 *
	 * @return array
	 */
	protected function getTableColumns(): array
	{
		return [
			'enabled' => [
				'title' => __('Enabled', $this->id),
				'type' => 'enabled',
				'sortable' => true,
			],
			'priority' => [
				'title' => __('Priority', $this->id),
				'type' => 'number',
				'sortable' => true,
			],
			'name' => [
				'title' => __('Name', $this->id),
				'type' => 'rule',
				'sortable' => true,
			],
		];
	}

	/**
	 * Returns items that match current search criteria
	 *
	 * @param array $args
	 * @return array
	 */
	protected function getItems(array $args): array
	{
		$items = $this->itemsSearcher
			->withSearch($args['search'] ?? '')
			->filter($this->itemsStorage->getList());

		$items = $this->itemsSorter
			->withOrderBy($args['orderby'] ?? '')
			->withOrderDirection($args['order'] ?? '')
			->filter($items);

		$this->totalNumberOfItems = count($items);

		return $this->itemsPaginator
			->withPage($args['page'] ?? 1)
			->withLimit($args['limit'] ?? 20)
			->filter($items);
	}

	/**
	 * Returns total number of items that match current search criteria
	 *
	 * @param array $args
	 * @return integer
	 */
	protected function getTotalItems(array $args): int
	{
		return $this->totalNumberOfItems;
	}

	/**
	 * includes scripts
	 *
	 * @return void
	 */
	protected function enqueueScripts(): void
	{
		$jsExt = defined('WP_DEBUG') && WP_DEBUG ? 'js' : 'min.js' ;

		wp_register_script(
			$this->id . '-RulesTable',
			plugins_url('assets/js/RulesTable.' . $jsExt, str_replace('phar://', '', $this->pluginPath)),
			['jquery'],
			$this->version
		);
		wp_enqueue_script($this->id . '-RulesTable');

		$settings = [
			'id' => $this->id,
			'tab' => 'rules',
			'ajaxurl' => admin_url('admin-ajax.php'),
		];

		wp_localize_script($this->id . '-RulesTable', 'rulesTableSettings', $settings);
	}

	/**
	 * returns inline style
	 *
	 * @return string
	 */
	protected function getInlineStyles(): string
	{
		$styles = '
			table.wp-list-table .column-name {
				width: auto;
			}
			.column-enabled, .column-priority {
				width: 90px;
			}
			.column-enabled {
				text-align: center;
			}
			.column-priority {
				width: 80px;
			}
		';

		return $styles;
	}
}
