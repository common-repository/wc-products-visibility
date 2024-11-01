<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\Options;

class UserRoles extends AbstractCachedOptions
{
	/**
	 * load options
	 *
	 * @return array
	 */
	protected function loadOptions(): array
	{
		if (false === function_exists('get_editable_roles') && file_exists(ABSPATH . '/wp-admin/includes/user.php')) {
			require_once(ABSPATH . '/wp-admin/includes/user.php');
		}

		$userRoles = [
			'not_logged_in' => __('Guest (Not Logged In)', $this->textDomain),
		];

		$roles = function_exists('get_editable_roles') ? get_editable_roles() : [];
		foreach ($roles as $role => $roleInfo) {
			$userRoles[$role] = $roleInfo['name'];
		}

		return $userRoles;
	}
}
