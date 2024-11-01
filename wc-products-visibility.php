<?php

/**
 * Plugin Name: Products and Variations Visibility for WooCommerce
 * Plugin URI: https://1teamsoftware.com/product/woocommerce-products-visibility-pro/
 * Description: Define rules when products and variations are visible or hidden.
 * Version: 1.0.7
 * Tested up to: 6.6
 * Requires PHP: 7.3
 * Author: OneTeamSoftware
 * Author URI: http://oneteamsoftware.com/
 * Developer: OneTeamSoftware
 * Developer URI: http://oneteamsoftware.com/
 * Text Domain: wc-products-visibility
 * Domain Path: /languages
 *
 * Copyright: © 2024 FlexRC, Canada.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace OneTeamSoftware\WC\ProductsVisibility;

defined('ABSPATH') || exit;

if (file_exists(__DIR__ . '/includes/autoloader.php')) {
	include_once __DIR__ . '/includes/autoloader.php';
}

if (class_exists(__NAMESPACE__ . '\\Plugin')) {
	(new Plugin(
		__FILE__,
		__('Products and Variations Visibility', 'wc-products-visibility'),
		sprintf(
			'<div class="oneteamsoftware notice notice-info inline"><p><strong>%s</strong> - %s</p><li><a href="%s" target="_blank">%s</a><br/><li><a href="%s" target="_blank">%s</a><p></p></div>',
			__('Products and Variations Visibility', 'wc-products-visibility'),
			__('Let\'s you define rules when products and variations are visible or hidden', 'wc-products-visibility'),
			'https://1teamsoftware.com/contact-us/',
			__('Do you have any questions or requests?', 'wc-products-visibility'),
			'https://wordpress.org/plugins/wc-products-visibility/',
			__('Do you like our plugin and can recommend it to others?', 'wc-products-visibility')
		),
		'1.0.7'
	)
	)->register();
} else if (is_admin()) {
	add_action(
		'admin_notices',
		function () {
			echo sprintf(
				'<div class="oneteamsoftware notice notice-error error"><p><strong>%s</strong> %s %s <a href="%s" target="_blank">%s</a> %s</p></div>',
				__('Products and Variations Visibility', 'wc-products-visibility'),
				__('plugin can not be loaded.', 'wc-products-visibility'),
				__('Please contact', 'wc-products-visibility'),
				'https://1teamsoftware.com/contact-us/',
				__('1TeamSoftware support', 'wc-products-visibility'),
				__('for assistance.', 'wc-products-visibility')
			);
		}
	);
}
