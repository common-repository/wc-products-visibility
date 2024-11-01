<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Admin;

use OneTeamSoftware\WC\ProductsVisibility\Visibility\Visibility;
use OneTeamSoftware\WC\ProductsVisibility\Visibility\VisibilityProperties;

class ActionFormFields implements FormFieldsInterface
{
	/**
	 * @var string
	 */
	private const PREFIX_MATCHED = 'matched_';

	/**
	 * @var string
	 */
	private const PREFIX_NOT_MATCHED = 'not_matched_';

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $proFeatureSuffix;

	/**
	 * constructor
	 *
	 * @param string $id
	 * @param string $proFeatureSuffix
	 */
	public function __construct(string $id, string $proFeatureSuffix)
	{
		$this->id = $id;
		$this->proFeatureSuffix = $proFeatureSuffix;
	}

	/**
	 * returns form fields
	 *
	 * @return array
	 */
	public function getFields(): array
	{
		$formFields = [
			'matched_visibility_start' => [
				'title' => __('Visibility when conditions have matched', $this->id),
				'type' => 'title',
				'id' => 'matched_visibility_start',
			],

			self::PREFIX_MATCHED . VisibilityProperties::CATALOG_VISIBILITY => [
				'id' => self::PREFIX_MATCHED . VisibilityProperties::CATALOG_VISIBILITY,
				'type' => 'select',
				'title' => __('Catalog Visibility', $this->id),
				'options' => $this->getVisibilityOptions(),
			],

			self::PREFIX_MATCHED . VisibilityProperties::VARIATION_VISIBILITY => [
				'id' => self::PREFIX_MATCHED . VisibilityProperties::VARIATION_VISIBILITY,
				'type' => 'select',
				'title' => __('Variation Visibility', $this->id),
				'options' => $this->getVisibilityOptions(),
			],

			self::PREFIX_MATCHED . VisibilityProperties::URL_VISIBILITY => [
				'id' => self::PREFIX_MATCHED . VisibilityProperties::URL_VISIBILITY,
				'type' => 'select',
				'title' => __('Product URL Visibility', $this->id),
				'desc' => $this->proFeatureSuffix,
				'options' => $this->getVisibilityOptions(),
				'custom_attributes' => empty($this->proFeatureSuffix) ? [] : ['disabled' => 'yes'],
			],

			self::PREFIX_MATCHED . VisibilityProperties::ROBOTS_VISIBILITY => [
				'id' => self::PREFIX_MATCHED . VisibilityProperties::ROBOTS_VISIBILITY,
				'type' => 'select',
				'title' => __('Search Engines Visibility', $this->id),
				'desc' => $this->proFeatureSuffix,
				'options' => $this->getVisibilityOptions(),
				'custom_attributes' => empty($this->proFeatureSuffix) ? [] : ['disabled' => 'yes'],
			],

			'matched_visibility_end' => [
				'type' => 'sectionend',
				'id' => 'matched_visibility_end'
			],

			'not_matched_visibility_start' => [
				'title' => __('Visibility when conditions have not matched', $this->id),
				'type' => 'title',
				'id' => 'not_matched_visibility_start'
			],

			self::PREFIX_NOT_MATCHED . VisibilityProperties::CATALOG_VISIBILITY => [
				'id' => self::PREFIX_NOT_MATCHED . VisibilityProperties::CATALOG_VISIBILITY,
				'type' => 'select',
				'title' => __('Catalog Visibility', $this->id),
				'options' => $this->getVisibilityOptions(),
			],

			self::PREFIX_NOT_MATCHED . VisibilityProperties::VARIATION_VISIBILITY => [
				'id' => self::PREFIX_NOT_MATCHED . VisibilityProperties::VARIATION_VISIBILITY,
				'type' => 'select',
				'title' => __('Variation Visibility', $this->id),
				'options' => $this->getVisibilityOptions(),
			],

			self::PREFIX_NOT_MATCHED . VisibilityProperties::URL_VISIBILITY => [
				'id' => self::PREFIX_NOT_MATCHED . VisibilityProperties::URL_VISIBILITY,
				'type' => 'select',
				'title' => __('Product URL Visibility', $this->id),
				'desc' => $this->proFeatureSuffix,
				'options' => $this->getVisibilityOptions(),
				'custom_attributes' => empty($this->proFeatureSuffix) ? [] : ['disabled' => 'yes'],
			],

			self::PREFIX_NOT_MATCHED . VisibilityProperties::ROBOTS_VISIBILITY => [
				'id' => self::PREFIX_NOT_MATCHED . VisibilityProperties::ROBOTS_VISIBILITY,
				'type' => 'select',
				'title' => __('Search Engines Visibility', $this->id),
				'desc' => $this->proFeatureSuffix,
				'options' => $this->getVisibilityOptions(),
				'custom_attributes' => empty($this->proFeatureSuffix) ? [] : ['disabled' => 'yes'],
			],

			'not_matched_visibility_end' => [
				'type' => 'sectionend',
				'id' => 'not_matched_visibility_end'
			],
		];

		return $formFields;
	}

	/**
	 * returns visibility options
	 *
	 * @return array
	 */
	private function getVisibilityOptions(): array
	{
		return [
			Visibility::KEEP_AS_IS => __('Keep as is', $this->id),
			Visibility::VISIBLE => __('Visible', $this->id),
			Visibility::HIDDEN => __('Hidden', $this->id),
		];
	}
}
