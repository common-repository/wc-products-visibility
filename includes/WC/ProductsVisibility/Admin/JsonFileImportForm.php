<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\WC\Admin\PageForm\AbstractPageForm;
use OneTeamSoftware\WP\ItemsStorage\ItemsStorage;

class JsonFileImportForm extends AbstractPageForm
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var ItemsStorage
	 */
	protected $itemsStorage;

	/**
	 * @var string
	 */
	protected $proFeatureSuffix;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param ItemsStorage $itemsStorage
	 * @param string $proFeatureSuffix
	 */
	public function __construct(string $id, ItemsStorage $itemsStorage, string $proFeatureSuffix)
	{
		$this->id = $id;
		$this->itemsStorage = $itemsStorage;
		$this->proFeatureSuffix = $proFeatureSuffix;

		parent::__construct($id . '-json-file-import', 'manage_woocommerce', $id);

		add_action('woocommerce_admin_field_file', [$this, 'displayFileField']);
	}

	/**
	 * returns fields for the plugin settings form
	 *
	 * @return array
	 */
	public function getFormFields(): array
	{
		$formFields = [
			'import_file_start' => [
				'type' => 'title',
				'id' => 'import_file_start',
			],

			'import_file' => [
				'id' => 'import_file',
				'title' => __('JSON File to Import', $this->id),
				'placeholder' => __('Choose a file to import?', $this->id),
				'type' => 'file',
				'desc' => __('Previously exported JSON file to import?', $this->id) . $this->proFeatureSuffix,
			],

			'import_file_end' => [
				'type' => 'sectionend',
				'id' => 'import_file_end',
			],

			'import' => [
				'id' => 'import',
				'title' => __('Import', $this->id),
				'type' => 'submit',
				'class' => 'button-primary',
				'custom_attributes' => empty($this->proFeatureSuffix) ? [] : ['disabled' => 'yes'],
			],
		];

		return $formFields;
	}

	/**
	 * renders HTML for file field
	 *
	 * @param array $value
	 * @return void
	 */
	public function displayFileField(array $value): void
	{
		// Custom attribute handling.
		$custom_attributes = [];

		if (!empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
			foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
				$custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
			}
		}

		$fieldValue = $value['value'] ?? '';
		$description = $value['desc'] ?? '';
		$tooltipHtml = $value['tooltipHtml'] ?? '';

		?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr($value['id'] ?? ''); ?>"><?php echo esc_html($value['title'] ?? ''); ?> <?php echo $tooltipHtml; // phpcs:ignore ?></label>
			</th>
			<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'] ?? '')); ?>">
				<input name="<?php echo esc_attr($value['id'] ?? ''); ?>" id="<?php echo esc_attr($value['id'] ?? ''); ?>" type="<?php echo esc_attr($value['type'] ?? ''); ?>" style="<?php echo esc_attr($value['css'] ?? ''); ?>" value="<?php echo esc_attr($fieldValue); ?>" class="<?php echo esc_attr($value['class'] ?? ''); ?>" placeholder="<?php echo esc_attr($value['placeholder'] ?? ''); ?>" <?php echo implode(' ', $custom_attributes); // phpcs:ignore ?> />
					<?php echo esc_html($value['suffix'] ?? ''); ?><p><?php echo $description; // WPCS: XSS ok. ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * return success message
	 *
	 * @return string
	 */
	protected function getSuccessMessageText(): string
	{
		return __('File has been successfully imported!', $this->id);
	}

	/**
	 * return error message
	 *
	 * @return string
	 */
	protected function getErrorMessageText(): string
	{
		return __('Unable to import from the uploaded file!', $this->id);
	}

	/**
	 * saves data and returns true or false and it can also modify input data
	 *
	 * @param array $data
	 * @return bool
	 */
	protected function saveFormData(array &$data): bool
	{
		return true;
	}
}
