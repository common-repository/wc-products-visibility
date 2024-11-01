<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\Rule\Matcher\Matchers;
use OneTeamSoftware\WC\Admin\LogExporter\LogExporter;
use OneTeamSoftware\WC\Logger\Logger;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitions;
use OneTeamSoftware\WP\Admin\OneTeamSoftware;
use OneTeamSoftware\WP\Admin\Page\AbstractPage;
use OneTeamSoftware\WP\Admin\Page\PageTab;
use OneTeamSoftware\WP\Admin\Page\PageTabs;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;
use OneTeamSoftware\WP\SettingsStorage\SettingsStorage;

class SettingsPage extends AbstractPage
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $pluginPath;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * @var LogExporter
	 */
	protected $logExporter;

	/**
	 * @var SettingsStorage
	 */
	protected $settingsStorage;

	/**
	 * @var ItemsStorage
	 */
	protected $itemsStorage;

	/**
	 * @var FormFieldsInterface
	 */
	protected $actionFormFields;

	/**
	 * @var ConditionDefinitions
	 */
	protected $conditionDefinitions;

	/**
	 * @var Matchers
	 */
	protected $matchers;

	/**
	 * @var string
	 */
	protected $proFeatureSuffix;

	/**
	 * @var PageTabs
	 */
	protected $pageTabs;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param string $title
	 * @param string $description
	 * @param string $pluginPath
	 * @param string $version
	 * @param Logger $logger
	 * @param LogExporter $logExporter
	 * @param SettingsStorage $settingsStorage
	 * @param ItemsStorage $itemsStorage
	 * @param FormFieldsInterface $actionFormFields
	 * @param ConditionDefinitions $conditionDefinitions
	 * @param Matchers $matchers
	 * @param string $proFeatureSuffix
	 */
	public function __construct(
		string $id,
		string $title,
		string $description,
		string $pluginPath,
		string $version,
		Logger $logger,
		LogExporter $logExporter,
		SettingsStorage $settingsStorage,
		ItemsStorage $itemsStorage,
		FormFieldsInterface $actionFormFields,
		ConditionDefinitions $conditionDefinitions,
		Matchers $matchers,
		string $proFeatureSuffix
	) {
		parent::__construct($id, 'oneteamsoftware', $title, $title, 'manage_woocommerce');

		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->pluginPath = $pluginPath;
		$this->version = $version;
		$this->logger = $logger;
		$this->logExporter = $logExporter;
		$this->settingsStorage = $settingsStorage;
		$this->itemsStorage = $itemsStorage;
		$this->actionFormFields = $actionFormFields;
		$this->conditionDefinitions = $conditionDefinitions;
		$this->matchers = $matchers;
		$this->proFeatureSuffix = $proFeatureSuffix;

		$this->pageTabs = new PageTabs($this->id);
	}

	/**
	 * registers settings page
	 *
	 * @return void
	 */
	public function register(): void
	{
		OneTeamSoftware::instance()->register();

		$this->addGeneralTab();
		$this->addRulesTab();
		$this->addEditRuleTab();
		$this->addImportRulesTab();
	}

	/**
	 * displays page
	 *
	 * @return void
	 */
	public function display(): void
	{
		$this->enqueueScripts();

		echo sprintf('<h1 class="wp-heading-inline">%s</h1>', $this->title);

		$this->pageTabs->display();
	}

	/**
	 * includes scripts
	 *
	 * @return void
	 */
	public function enqueueScripts(): void
	{
		$cssExt = defined('WP_DEBUG') && WP_DEBUG ? 'css' : 'min.css' ;
		$jsExt = defined('WP_DEBUG') && WP_DEBUG ? 'js' : 'min.js' ;

		wp_register_style(
			$this->id . '-SettingsPage',
			plugins_url('assets/css/SettingsPage.' . $cssExt, str_replace('phar://', '', $this->pluginPath)),
			['wp-jquery-ui-dialog'],
			$this->version
		);
		wp_enqueue_style($this->id . '-SettingsPage');

		wp_register_style(
			$this->id . '-switchify',
			plugins_url('assets/css/switchify.' . $cssExt, str_replace('phar://', '', $this->pluginPath)),
			[],
			$this->version
		);
		wp_enqueue_style($this->id . '-switchify');

		wp_register_script(
			$this->id . '-switchify',
			plugins_url('assets/js/switchify.' . $jsExt, str_replace('phar://', '', $this->pluginPath)),
			['jquery'],
			$this->version
		);
		wp_enqueue_script($this->id . '-switchify');
	}

	/**
	 * adds general tab
	 *
	 * @return void
	 */
	protected function addGeneralTab(): void
	{
		$this->pageTabs->addTab(
			new PageTab(
				'general',
				'manage_woocommerce',
				__('General Settings', $this->id),
				$this->getGeneralForm()
			)
		);
	}

	/**
	 * returns general form
	 *
	 * @return GeneralForm
	 */
	protected function getGeneralForm(): GeneralForm
	{
		return new GeneralForm($this->id, $this->description, $this->logExporter, $this->settingsStorage);
	}

	/**
	 * adds rules tab
	 *
	 * @return void
	 */
	protected function addRulesTab(): void
	{
		$this->pageTabs->addTab(
			new PageTab(
				'rules',
				'manage_woocommerce',
				__('Rules', $this->id),
				$this->getRulesTable(),
				__('Rules', $this->id),
				[
					[
						'title' => __('Add Rule', $this->id),
						'url' => admin_url('admin.php?page=' . $this->id . '&tab=edit'),
					],
					[
						'title' => __('Import Rules', $this->id),
						'url' => admin_url('admin.php?page=' . $this->id . '&tab=import'),
					],
				]
			)
		);
	}

	/**
	 * returns RulesTable
	 *
	 * @return RulesTable
	 */
	protected function getRulesTable(): RulesTable
	{
		return new RulesTable($this->id, $this->pluginPath, $this->version, $this->logger, $this->itemsStorage);
	}

	/**
	 * adds edit rule tab
	 *
	 * @return void
	 */
	protected function addEditRuleTab(): void
	{
		if ('edit' !== ($_REQUEST['tab'] ?? '')) {
			return;
		}

		$pageForm = new EditRuleForm(
			$this->id,
			$this->pluginPath,
			$this->version,
			$this->itemsStorage,
			$this->actionFormFields,
			$this->conditionDefinitions,
			$this->matchers,
			$this->proFeatureSuffix
		);

		$tabTitle = __('Edit Rule', $this->id);
		if (empty($pageForm->getRequestedRuleId())) {
			$tabTitle = __('Add Rule', $this->id);
		}

		$this->pageTabs->addTab(
			new PageTab(
				'edit',
				'manage_woocommerce',
				$tabTitle,
				$pageForm
			)
		);
	}

	/**
	 * adds edit rule tab
	 *
	 * @return void
	 */
	protected function addImportRulesTab(): void
	{
		if ('import' !== ($_REQUEST['tab'] ?? '')) {
			return;
		}

		$this->pageTabs->addTab(
			new PageTab(
				'import',
				'manage_woocommerce',
				__('Import Rules', $this->id),
				$this->getJsonFileImportForm()
			)
		);
	}

	/**
	 * creates and returns JsonFileImportForm
	 *
	 * @return JsonFileImportForm
	 */
	protected function getJsonFileImportForm(): JsonFileImportForm
	{
		return new JsonFileImportForm(
			$this->id,
			$this->itemsStorage,
			$this->proFeatureSuffix
		);
	}
}
