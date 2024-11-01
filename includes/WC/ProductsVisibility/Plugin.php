<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility;

use OneTeamSoftware\Cache\Cache;
use OneTeamSoftware\Mutex\FileMutex;
use OneTeamSoftware\Rule\Matcher\Matchers;
use OneTeamSoftware\WC\Admin\LogExporter\LogExporter;
use OneTeamSoftware\WC\Logger\Logger;
use OneTeamSoftware\WC\Options\Attributes as OptionsAttributes;
use OneTeamSoftware\WC\Options\Categories as OptionsCategories;
use OneTeamSoftware\WC\Options\Products as OptionsProducts;
use OneTeamSoftware\WC\Options\ProductTypes as OptionsProductTypes;
use OneTeamSoftware\WC\Options\ShippingClasses as OptionsShippingClasses;
use OneTeamSoftware\WC\Options\Tags as OptionsTags;
use OneTeamSoftware\WC\Options\Taxonomies as OptionsTaxonomies;
use OneTeamSoftware\WC\Options\TaxonomyArrayBuilder;
use OneTeamSoftware\WC\Options\UserRoles as OptionsUserRoles;
use OneTeamSoftware\WC\ProductsVisibility\Admin\ActionFormFields;
use OneTeamSoftware\WC\ProductsVisibility\Admin\FormFieldsInterface;
use OneTeamSoftware\WC\ProductsVisibility\Admin\SettingsPage;
use OneTeamSoftware\WC\ProductsVisibility\Visibility\VisibilityPropertiesFactory;
use OneTeamSoftware\WC\Rule\ConditionDefinition\Attributes as ConditionDefinitionAttributes;
use OneTeamSoftware\WC\Rule\ConditionDefinition\Categories as ConditionDefinitionCategories;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitions;
use OneTeamSoftware\WC\Rule\ConditionDefinition\FromDate as ConditionDefinitionFromDate;
use OneTeamSoftware\WC\Rule\ConditionDefinition\FromTime as ConditionDefinitionFromTime;
use OneTeamSoftware\WC\Rule\ConditionDefinition\LowStock as ConditionDefinitionLowStock;
use OneTeamSoftware\WC\Rule\ConditionDefinition\OnBackorder as ConditionDefinitionOnBackorder;
use OneTeamSoftware\WC\Rule\ConditionDefinition\OnSale as ConditionDefinitionOnSale;
use OneTeamSoftware\WC\Rule\ConditionDefinition\Products as ConditionDefinitionProducts;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ProductTypes as ConditionDefinitionProductTypes;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ShippingClasses as ConditionDefinitionShippingClasses;
use OneTeamSoftware\WC\Rule\ConditionDefinition\StockQuantityBelow as ConditionDefinitionStockQuantityBelow;
use OneTeamSoftware\WC\Rule\ConditionDefinition\StockStatus as ConditionDefinitionStockStatus;
use OneTeamSoftware\WC\Rule\ConditionDefinition\Tags as ConditionDefinitionTags;
use OneTeamSoftware\WC\Rule\ConditionDefinition\Taxonomies as ConditionDefinitionTaxonomies;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ToDate as ConditionDefinitionToDate;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ToTime as ConditionDefinitionToTime;
use OneTeamSoftware\WC\Rule\ConditionDefinition\UserRoles as ConditionDefinitionUserRoles;
use OneTeamSoftware\WC\Rule\Matcher\Category as MatcherCategory;
use OneTeamSoftware\WC\Rule\Matcher\OnBackorder as MatcherOnBackorder;
use OneTeamSoftware\WC\Rule\Matcher\OnSale as MatcherOnSale;
use OneTeamSoftware\WC\Rule\Matcher\Product as MatcherProduct;
use OneTeamSoftware\WC\Rule\Matcher\ProductType as MatcherProductType;
use OneTeamSoftware\WC\Rule\RuleFactory;
use OneTeamSoftware\WC\Rule\RuleMatcher;
use OneTeamSoftware\WP\Cache\Storage\Transient;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;
use OneTeamSoftware\WP\PluginDependency\PluginDependency;
use OneTeamSoftware\WP\SettingsStorage\SettingsStorage;

class Plugin
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $pluginPath;

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
	protected $version;

	/**
	 * @var TaxonomyArrayBuilder
	 */
	protected $taxonomyArrayBuilder;

	/**
	 * @var Cache
	 */
	protected $cache;

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
	 * @var ConditionDefinitions
	 */
	protected $conditionDefinitions;

	/**
	 * @var Matchers
	 */
	protected $matchers;

	/**
	 * @var RuleFactory
	 */
	protected $ruleFactory;

	/**
	 * @var RuleMatcher
	 */
	protected $ruleMatcher;

	/**
	 * @var VisibilityPropertiesFactory
	 */
	protected $visibilityPropertiesFactory;

	/**
	 * @var HooksPro
	 */
	protected $hooks;

	/**
	 * @var string
	 */
	protected $proFeatureSuffix;

	/**
	 * @var array
	 */
	protected $defaultSettings;

	/**
	 * constructor
	 *
	 * @param string $pluginPath
	 * @param string $title
	 * @param string $description
	 * @param string $version
	 */
	public function __construct(
		string $pluginPath,
		string $title = '',
		string $description = '',
		string $version = null
	) {
		$this->id = preg_replace('/-pro$/', '', basename($pluginPath, '.php'));
		$this->pluginPath = $pluginPath;
		$this->title = $title;
		$this->description = $description;
		$this->version = $version;
		$this->taxonomyArrayBuilder = new TaxonomyArrayBuilder();
		$this->cache = new Cache(new Transient());
		$this->logger = new Logger($this->id);
		$this->logExporter = new LogExporter($this->id, get_class($this));
		$this->settingsStorage = new SettingsStorage($this->id, new FileMutex($this->id));
		$this->itemsStorage = new ItemsStorage($this->settingsStorage, 'rules');
		$this->conditionDefinitions = new ConditionDefinitions();
		$this->matchers = new Matchers();
		$this->ruleFactory = new RuleFactory();
		$this->ruleFactory
			->withLogger($this->logger)
			->withConditionDefinitions($this->conditionDefinitions)
			->withMatcher($this->matchers);
		$this->ruleMatcher = new RuleMatcher($this->ruleFactory, $this->logger);
		$this->visibilityPropertiesFactory = new VisibilityPropertiesFactory($this->ruleMatcher, $this->cache, $this->logger);
		$this->visibilityPropertiesFactory->withListOfRuleSettings($this->itemsStorage->getList());

		$this->proFeatureSuffix = sprintf(
			' <strong>(%s <a href="%s" target="_blank">%s</a>)</strong>',
			__('Requires', $this->id),
			'https://1teamsoftware.com/product/' . preg_replace('/wc/', 'woocommerce', $this->id) . '-pro/',
			__('PRO Version', $this->id)
		);

		$this->defaultSettings = [
			'debug' => false,
			'cache' => true,
			'cache_expiration_in_secs' => DAY_IN_SECONDS,
		];

		$this->initHooks();
	}

	/**
	 * registers plugin
	 *
	 * @return void
	 */
	public function register(): void
	{
		if (false === $this->canRegister()) {
			return;
		}

		add_action('plugins_loaded', [$this, 'onPluginsLoaded'], PHP_INT_MAX, 0);
		add_filter($this->id . '_settingsstorage_get', [$this, 'addDefaultSettings'], 1, 1);

		$this->logExporter->register();
		$this->hooks->register();
	}

	/**
	 * adds link to settings page
	 *
	 * @param array $links
	 * @return array
	 */
	public function onPluginActionLinks(array $links): array
	{
		$link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=' . $this->id), __('Settings', $this->id));
		array_unshift($links, $link);
		return $links;
	}

	/**
	 * handles plugins loaded hook
	 *
	 * @return void
	 */
	public function onPluginsLoaded(): void
	{
		$this->setPluginSettings($this->settingsStorage->get());
		$this->initConditionDefinitions();
		$this->initMatchers();
		$this->initAdminFeatures();
	}

	/**
	 * adds default settings to the given settings
	 *
	 * @param array $settings
	 * @return array
	 */
	public function addDefaultSettings(array $settings): array
	{
		return array_merge($this->defaultSettings, $settings);
	}

	/**
	 * sets plugin settings
	 *
	 * @param array $settings
	 * @return void
	 */
	protected function setPluginSettings(array $settings): void
	{
		$this->cache->setUseCache(filter_var($settings['cache'] ?? true, FILTER_VALIDATE_BOOLEAN));
		$this->cache->setDefaultExpiresAfter(intval($settings['cache_expiration_in_secs'] ?? DAY_IN_SECONDS));
		$this->logger->setEnabled(filter_var($settings['debug'] ?? false, FILTER_VALIDATE_BOOLEAN));
	}

	/**
	 * loads condition definitions
	 *
	 * @return void
	 */
	protected function initConditionDefinitions(): void
	{
		$optionsTaxonomies = new OptionsTaxonomies($this->id, $this->taxonomyArrayBuilder, $this->cache);

		$this->conditionDefinitions->add(
			new ConditionDefinitionProducts($this->id, new OptionsProducts($this->id, $this->cache))
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionProductTypes(
				$this->id,
				new OptionsProductTypes($this->id, $this->taxonomyArrayBuilder, $this->cache)
			)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionCategories(
				$this->id,
				new OptionsCategories($this->id, $this->taxonomyArrayBuilder, $this->cache)
			)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionOnBackorder($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionOnSale($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionAttributes($this->id, new OptionsAttributes($this->id, $optionsTaxonomies))
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionShippingClasses(
				$this->id,
				new OptionsShippingClasses($this->id, $this->taxonomyArrayBuilder, $this->cache)
			)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionTags(
				$this->id,
				new OptionsTags($this->id, $this->taxonomyArrayBuilder, $this->cache)
			)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionTaxonomies($this->id, $optionsTaxonomies)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionUserRoles($this->id, new OptionsUserRoles($this->id, $this->cache))
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionStockStatus($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionStockQuantityBelow($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionLowStock($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionFromDate($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionToDate($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionFromTime($this->id)
		);
		$this->conditionDefinitions->add(
			new ConditionDefinitionToTime($this->id)
		);
	}

	/**
	 * loads matchers
	 *
	 * @return void
	 */
	protected function initMatchers(): void
	{
		$this->matchers->add(new MatcherProduct($this->logger));
		$this->matchers->add(new MatcherProductType($this->logger));
		$this->matchers->add(new MatcherCategory($this->logger));
		$this->matchers->add(new MatcherOnBackorder($this->logger));
		$this->matchers->add(new MatcherOnSale($this->logger));
	}

	/**
	 * initializes hooks
	 *
	 * @return void
	 */
	protected function initHooks(): void
	{
		$this->hooks = new Hooks($this->id, $this->logger, $this->visibilityPropertiesFactory);
	}

	/**
	 * initializes admin features
	 *
	 * @return void
	 */
	protected function initAdminFeatures(): void
	{
		if (false === is_admin()) {
			return;
		}

		add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), [$this, 'onPluginActionLinks'], 1, 1);

		$this->initSettingsPage();
	}

	/**
	 * initializes settings page
	 *
	 * @return void
	 */
	protected function initSettingsPage(): void
	{
		(new SettingsPage(
			$this->id,
			$this->title,
			$this->description,
			$this->pluginPath,
			$this->version,
			$this->logger,
			$this->logExporter,
			$this->settingsStorage,
			$this->itemsStorage,
			$this->getActionFormFields(),
			$this->conditionDefinitions,
			$this->matchers,
			$this->getProFeatureSuffix()
		))->register();
	}

	/**
	 * returns action form fields
	 *
	 * @return FormFieldsInterface
	 */
	protected function getActionFormFields(): FormFieldsInterface
	{
		return new ActionFormFields($this->id, $this->getProFeatureSuffix());
	}

	/**
	 * returns pro feature suffix
	 *
	 * @return string
	 */
	protected function getProFeatureSuffix(): string
	{
		return $this->proFeatureSuffix;
	}

	/**
	 * returns true when plugin can register
	 *
	 * @return bool
	 */
	protected function canRegister(): bool
	{
		$pluginDependency = (new PluginDependency($this->id, $this->title));
		$pluginDependency
			->register()
			->add(
				'woocommerce/woocommerce.php',
				__('WooCommerce', $this->id),
				admin_url('/plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=600&height=550')
			);

		if (false === $pluginDependency->validate()) {
			return false;
		}

		return false === $this->isProVersionEnabled();
	}

	/**
	 * returns true when pro version is enabled
	 *
	 * @return bool
	 */
	protected function isProVersionEnabled(): bool
	{
		$proPluginName = preg_replace('/(\.php|\/)/i', '-pro\\1', plugin_basename($this->pluginPath));
		if (is_plugin_active($proPluginName)) {
			return true;
		}

		return false;
	}
}
