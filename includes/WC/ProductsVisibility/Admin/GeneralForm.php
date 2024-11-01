<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\WC\Admin\LogExporter\LogExporter;
use OneTeamSoftware\WC\Admin\PageForm\AbstractPageForm;
use OneTeamSoftware\WP\SettingsStorage\SettingsStorage;

class GeneralForm extends AbstractPageForm
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var LogExporter
	 */
	protected $logExporter;

	/**
	 * @var SettingsStorage
	 */
	protected $settingsStorage;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param string $description
	 * @param LogExporter $logExporter
	 * @param SettingsStorage $settingsStorage
	 */
	public function __construct(
		string $id,
		string $description,
		LogExporter $logExporter,
		SettingsStorage $settingsStorage
	) {
		$this->id = $id;
		$this->description = $description;
		$this->logExporter = $logExporter;
		$this->settingsStorage = $settingsStorage;

		parent::__construct($id . '-general', 'manage_woocommerce', $id);
	}

	/**
	 * Returns fields for the plugin settings form
	 *
	 * @return array
	 */
	public function getFormFields(): array
	{
		$formFields = [
			'description' => [
				'id' => 'description',
				'type' => 'title',
				'desc' => $this->description,
			],
			'description_end' => [
				'type' => 'sectionend',
			],
			$this->id . '_settings_start' => [
				'type' => 'title',
				'id' => $this->id . '_settings'
			],
		];

		$formFields += $this->getFormFieldsBefore();

		$formFields += [
			'debug' => [
				'id' => 'debug',
				'title' => __('Debug', $this->id),
				'type' => 'checkbox',
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'optional' => true,
				'desc' => sprintf(
					'<strong>%s</strong><br/> %s <a href="https://www.loom.com/" target="_blank">loom.com</a>, %s 
					<a href="%s" target="_blank">%s</a> %s <a href="%s" target="_blank">%s</a> %s.',
					__('Do you experience any issues?', $this->id),
					__('Enable a debug mode, reproduce the issue, while recording screen with', $this->id),
					__('then', $this->id),
					$this->logExporter->getExportUrl(),
					__('click to download a log file', $this->id),
					__('and send it via our', $this->id),
					'https://1teamsoftware.com/contact-us/',
					__('contact form', $this->id),
					__('with the detailed description of the issue', $this->id),
				),
			],

			'cache' => [
				'id' => 'cache',
				'title' => __('Use Cache', $this->id),
				'type' => 'checkbox',
				'filter' => FILTER_VALIDATE_BOOLEAN,
				// phpcs:ignore
				'desc' => __('Caching improves performance of rules matching by storing previous match result and using it instead of matching every single rule every time website is requested.', $this->id),
			],
			'cache_expiration_in_secs' => [
				'id' => 'cache_expiration_in_secs',
				'title' => __('Cache Expiration (secs)', $this->id),
				'type' => 'number',
				'custom_attributes' => [
					'min' => 0,
					'step' => 1,
				],
				'filter' => FILTER_VALIDATE_INT,
				// phpcs:ignore
				'desc' => __('Rules matching results will be cached for a given amount of seconds and invalidated after that time.', $this->id),
			],

			$this->id . '_settings_end' => [
				'type' => 'sectionend',
				'id' => $this->id . '_settings'
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
	 * returns form fields at the begining of the form
	 *
	 * @return array
	 */
	protected function getFormFieldsBefore(): array
	{
		return [];
	}

	/**
	 * Return success message
	 *
	 * @return string
	 */
	protected function getSuccessMessageText(): string
	{
		return __('Settings have been successfully saved', $this->id);
	}

	/**
	 * returns data that will be displayed in the form
	 *
	 * @return array
	 */
	protected function getFormData(): array
	{
		$inputData = array_map('sanitize_text_field', wp_unslash($_REQUEST));

		return array_merge($this->settingsStorage->get(), $inputData);
	}

	/**
	 * Saves data and returns true or false and it can also modify input data
	 *
	 * @param array $data
	 * @return bool
	 */
	protected function saveFormData(array &$data): bool
	{
		$this->settingsStorage->update($data);

		do_action($this->id . '_settings_saved', $data);

		return true;
	}
}
