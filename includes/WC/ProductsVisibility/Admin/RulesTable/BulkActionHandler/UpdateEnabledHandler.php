<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin\RulesTable\BulkActionHandler;

use OneTeamSoftware\Logger\LoggerInterface;
use OneTeamSoftware\WP\Admin\Notices\Notices;
use OneTeamSoftware\WP\Admin\Table\BulkActionHandler\BulkActionHandlerInterface;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;

class UpdateEnabledHandler implements BulkActionHandlerInterface
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
	 * @var bool
	 */
	private $enabled;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param LoggerInterface $logger
	 * @param Notices $notices
	 * @param ItemsStorage $itemsStorage
	 * @param bool $enabled
	 */
	public function __construct(
		string $id,
		LoggerInterface $logger,
		Notices $notices,
		ItemsStorage $itemsStorage,
		bool $enabled
	) {
		$this->id = $id;
		$this->logger = $logger;
		$this->notices = $notices;
		$this->itemsStorage = $itemsStorage;
		$this->enabled = $enabled;
	}

	/**
	 * handles bulk action and returns true or false
	 *
	 * @param array $ids
	 * @return boolean
	 */
	public function handle(array $ids): bool
	{
		$this->logger->debug(__FILE__, __LINE__, 'Update enabled to %d for IDs: %s', $this->enabled, print_r($ids, true)); //phpcs:ignore

		$numberOfItemsUpdated = 0;

		foreach ($ids as $id) {
			$item = $this->itemsStorage->get($id);
			$item['enabled'] = $this->enabled;
			if ($this->itemsStorage->update($id, $item)) {
				$numberOfItemsUpdated++;
				$this->logger->debug(__FILE__, __LINE__, '%s has been updated', $id);
			} else {
				$this->logger->debug(__FILE__, __LINE__, 'Failed to update %s', $id);
			}
		}

		if ($numberOfItemsUpdated === count($ids)) {
			$this->notices->type = 'success';
			$this->notices->add(__('Rules have been successfully updated', $this->id));
		} else {
			$this->notices->type = 'error';
			$this->notices->add(__('Failed to update some of the rules', $this->id));
		}

		return true;
	}
}
