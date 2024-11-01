<?php

declare(strict_types=1);

namespace OneTeamSoftware\WC\ProductsVisibility\Visibility;

class Visibility
{
	/**
	 * @var string
	 */
	public const KEEP_AS_IS = '';

	/**
	 * @var string
	 */
	public const VISIBLE = 'visible';

	/**
	 * var string
	 */
	public const HIDDEN = 'hidden';

	/**
	 * @var string
	 */
	private $visibility;

	/**
	 * constructor
	 *
	 * @param string $visibility
	 */
	public function __construct(string $visibility = self::KEEP_AS_IS)
	{
		$this->visibility = self::KEEP_AS_IS;
		$this->setVisibility($visibility);
	}

	/**
	 * returns true when current visibility is set to VISIBLE or input visible is TRUE and visibility is set to KEEP_AS_IS
	 *
	 * @param boolean $visible
	 * @return boolean
	 */
	public function isVisible(bool $visible): bool
	{
		return self::KEEP_AS_IS === $this->visibility ? $visible : (self::VISIBLE === $this->visibility);
	}

	/**
	 * returns visibility value
	 *
	 * @return string
	 */
	public function getVisibility(): string
	{
		return $this->visibility;
	}

	/**
	 * sets visibility value
	 *
	 * @param string $visibility
	 * @return void
	 */
	public function setVisibility(string $visibility): void
	{
		if (false === $this->isValid($visibility)) {
			return;
		}

		if (self::KEEP_AS_IS === $visibility) {
			return;
		}

		$this->visibility = $visibility;
	}

	/**
	 * returns true when a given visibility is valid
	 *
	 * @param string $visibility
	 * @return boolean
	 */
	private function isValid(string $visibility): bool
	{
		if ($visibility === self::KEEP_AS_IS) {
			return true;
		}

		if ($visibility === self::VISIBLE) {
			return true;
		}

		if ($visibility === self::HIDDEN) {
			return true;
		}

		return false;
	}
}
