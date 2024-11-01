<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\BulkActionHandler;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\WP\Admin\Notices\Notices;
use OneTeamSoftware\WP\Admin\Table\BulkActionHandler\BulkActionHandlerInterface;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;

class DeleteHandler implements BulkActionHandlerInterface
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var Notices
	 */
	private $notices;

	/**
	 * @var ItemsStorage
	 */
	private $itemsStorage;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param LoggerInterface $logger
	 * @param Notices $notices
	 * @param ItemsStorage $itemsStorage
	 */
	public function __construct(
		string $id,
		LoggerInterface $logger,
		Notices $notices,
		ItemsStorage $itemsStorage
	) {
		$this->id = $id;
		$this->logger = $logger;
		$this->notices = $notices;
		$this->itemsStorage = $itemsStorage;
	}

	/**
	 * handles bulk action and returns true or false
	 *
	 * @param array $ids
	 * @return boolean
	 */
	public function handle(array $ids): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'Delete items with IDs: %s', print_r($ids, true)); //phpcs:ignore

		$numberOfItemsUpdated = 0;

		foreach ($ids as $id) {
			if ($this->itemsStorage->delete($id)) {
				$numberOfItemsUpdated++;
				$this->logger->debug(__FILE__, __LINE__, '%s has been deleted', $id);
			} else {
				$this->logger->debug(__FILE__, __LINE__, 'Failed to delete %s', $id);
			}
		}

		if ($numberOfItemsUpdated === count($ids)) {
			$this->notices->type = 'success';
			$this->notices->add(__('Rules have been successfully deleted', $this->id));
		} else {
			$this->notices->type = 'error';
			$this->notices->add(__('Failed to delete some of the rules', $this->id));
		}

		return true;
	}
}
