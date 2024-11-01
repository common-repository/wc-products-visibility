<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\Rule\Matcher\Matchers;
use OneTeamSoftware\WC\Admin\PageForm\AbstractPageForm;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitionInterface;
use OneTeamSoftware\WC\Rule\ConditionDefinition\ConditionDefinitions;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;

class EditRuleForm extends AbstractPageForm
{
	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $pluginPath;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var ItemsStorage
	 */
	private $itemsStorage;

	/**
	 * @var FormFieldsInterface
	 */
	private $actionFormFields;

	/**
	 * @var ConditionDefinitions
	 */
	private $conditionDefinitions;

	/**
	 * @var Matchers
	 */
	private $matchers;

	/**
	 * @var string
	 */
	private $proFeatureSuffix;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param string $pluginPath
	 * @param string $version
	 * @param ItemsStorage $itemsStorage
	 * @param FormFieldsInterface $actionFormFields
	 * @param ConditionDefinitions $conditionDefinitions
	 * @param Matchers $matchers
	 * @param string $proFeatureSuffix
	 */
	public function __construct(
		string $id,
		string $pluginPath,
		string $version,
		ItemsStorage $itemsStorage,
		FormFieldsInterface $actionFormFields,
		ConditionDefinitions $conditionDefinitions,
		Matchers $matchers,
		string $proFeatureSuffix
	) {
		$this->id = $id;
		$this->pluginPath = $pluginPath;
		$this->version = $version;
		$this->itemsStorage = $itemsStorage;
		$this->actionFormFields = $actionFormFields;
		$this->conditionDefinitions = $conditionDefinitions;
		$this->matchers = $matchers;
		$this->proFeatureSuffix = $proFeatureSuffix;

		parent::__construct($id . '-edit', 'manage_woocommerce', $id);
	}

	/**
	 * Displays this form
	 *
	 * @return void
	 */
	public function display(): void
	{
		$this->enqueueScripts();

		parent::display();
	}

	/**
	 * returns fields for the plugin settings form
	 *
	 * @return array
	 */
	public function getFormFields(): array
	{
		$formFields = [
			'header_start' => [
				'type' => 'title',
				'id' => 'header_start'
			],

			'enabled' => [
				'id' => 'enabled',
				'type' => 'checkbox',
				'title' => __('Enabled', $this->id),
				'desc' => __('This rule will be evaluated only when it is enabled', $this->id),
				'default' => '1',
			],

			'priority' => [
				'id' => 'priority',
				'type' => 'number',
				'title' => __('Priority', $this->id),
				'desc' => __('Rules are executed in ascending priority order', $this->id),
				'default' => '10',
				'custom_attributes' => ['step' => 1, 'min' => 1],
				'filter' => FILTER_VALIDATE_INT,
				'filter_options' => ['options' => ['min_range' => 1]],
			],

			'name' => [
				'id' => 'name',
				'type' => 'text',
				'title' => __('Name', $this->id),
				'desc' => __('Name is used to help to easily identify this rule', $this->id),
				'default' => '',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => ['options' => ['regexp' => '/^.{1,255}$/']],
			],

			'header_end' => [
				'type' => 'sectionend',
				'id' => 'header_end'
			],
		];

		$formFields += $this->actionFormFields->getFields();

		$formFields += [
			'conditions_start' => [
				'title' => __('Conditions', $this->id),
				'type' => 'title',
				'id' => 'conditions'
			],
		];

		foreach ($this->conditionDefinitions as $conditionDefinition) {
			$formFields += $this->getConditionDefinitionFormFields($conditionDefinition);
		}

		$formFields += [
			'conditions_end' => [
				'type' => 'sectionend',
				'id' => 'conditions'
			],

			'save' => [
				'id' => 'save',
				'title' => __('Save Changes', $this->id),
				'type' => 'submit',
				'class' => 'button-primary',
			],
		];

		return $formFields;
	}

	/**
	 * returns requested rule id
	 *
	 * @return string
	 */
	public function getRequestedRuleId(): string
	{
		return sanitize_key($_REQUEST['id'] ?? '');
	}

	/**
	 * Return success message
	 *
	 * @return string
	 */
	protected function getSuccessMessageText(): string
	{
		return __('Rule have been successfully saved', $this->id);
	}

	/**
	 * returns data that will be displayed in the form
	 *
	 * @return array
	 */
	protected function getFormData(): array
	{
		$data = [];
		if (false === empty($this->getRequestedRuleId())) {
			$data = $this->itemsStorage->get($this->getRequestedRuleId());
		}

		$inputData = array_map('sanitize_text_field', wp_unslash($_REQUEST));

		return array_merge($data, $inputData);
	}

	/**
	 * saves data and returns true or false and it can also modify input data
	 *
	 * @param array $data
	 * @return bool
	 */
	protected function saveFormData(array &$data): bool
	{
		$ruleId = $this->getRequestedRuleId();
		if (false === empty($ruleId)) {
			return $this->itemsStorage->update($ruleId, $data);
		}

		$data['id'] = $this->itemsStorage->add($data);

		return false === empty($data['id']);
	}

	/**
	 * returns form fields for a given condition definition
	 *
	 * @param ConditionDefinitionInterface $conditionDefinition
	 * @return array
	 */
	private function getConditionDefinitionFormFields(ConditionDefinitionInterface $conditionDefinition): array
	{
		$formFields = $conditionDefinition->getFormFields();

		if (
			class_exists($conditionDefinition->getMatcherId()) &&
			$this->matchers->has($conditionDefinition->getMatcherId())
		) {
			return $formFields;
		}

		foreach ($formFields as &$formField) {
			$formField['desc'] = sprintf(
				'%s %s',
				isset($formField['desc']) ? $formField['desc'] . '<br/>' : '',
				$this->proFeatureSuffix
			);
			$formField['custom_attributes']['disabled'] = 'yes';
		}

		return $formFields;
	}

	/**
	 * includes scripts
	 *
	 * @return void
	 */
	private function enqueueScripts(): void
	{
		$jsExt = defined('WP_DEBUG') && WP_DEBUG ? 'js' : 'min.js' ;

		wp_register_script(
			$this->id . '-EditRuleForm',
			plugins_url('assets/js/EditRuleForm.' . $jsExt, str_replace('phar://', '', $this->pluginPath)),
			['jquery'],
			$this->version
		);
		wp_enqueue_script($this->id . '-EditRuleForm');
	}
}
