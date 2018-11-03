<?php namespace ALttP\World;

use ALttP\Region;
use ALttP\World;

class Open extends World {
	/**
	 * Create a new world and initialize all of the Regions within it
	 *
	 * @param string $difficulty difficulty from config to apply to randomization
	 * @param string $logic Ruleset to use when deciding if Locations can be reached
	 * @param string $goal Goal of the game
	 * @param string $variation modifications to difficulty
	 *
	 * @return void
	 */
	public function __construct($difficulty = 'normal', $logic = 'NoGlitches', $goal = 'ganon', $variation = 'none') {
		$this->difficulty = $difficulty;
		$this->variation = $variation;
		$this->logic = $logic;
		$this->goal = $goal;

		$this->regions = [
			'North East Light World' => new Region\Standard\LightWorld\NorthEast($this),
			'North West Light World' => new Region\Standard\LightWorld\NorthWest($this),
			'South Light World' => new Region\Standard\LightWorld\South($this),
			'Escape' => new Region\Open\HyruleCastleEscape($this),
			'Eastern Palace' => new Region\Standard\EasternPalace($this),
			'Desert Palace' => new Region\Standard\DesertPalace($this),
			'West Death Mountain' => new Region\Standard\LightWorld\DeathMountain\West($this),
			'East Death Mountain' => new Region\Standard\LightWorld\DeathMountain\East($this),
			'Tower of Hera' => new Region\Standard\TowerOfHera($this),
			'Hyrule Castle Tower' => new Region\Standard\HyruleCastleTower($this),
			'East Dark World Death Mountain' => new Region\Standard\DarkWorld\DeathMountain\East($this),
			'West Dark World Death Mountain' => new Region\Standard\DarkWorld\DeathMountain\West($this),
			'North East Dark World' => new Region\Standard\DarkWorld\NorthEast($this),
			'North West Dark World' => new Region\Standard\DarkWorld\NorthWest($this),
			'South Dark World' => new Region\Standard\DarkWorld\South($this),
			'Mire' => new Region\Standard\DarkWorld\Mire($this),
			'Palace of Darkness' => new Region\Standard\PalaceOfDarkness($this),
			'Swamp Palace' => new Region\Standard\SwampPalace($this),
			'Skull Woods' => new Region\Standard\SkullWoods($this),
			'Thieves Town' => new Region\Standard\ThievesTown($this),
			'Ice Palace' => new Region\Standard\IcePalace($this),
			'Misery Mire' => new Region\Standard\MiseryMire($this),
			'Turtle Rock' => new Region\Standard\TurtleRock($this),
			'Ganons Tower' => new Region\Standard\GanonsTower($this),
			'Medallions' => new Region\Standard\Medallions($this),
			'Fountains' => new Region\Standard\Fountains($this),

			'Central Crateria' => new Region\Standard\SuperMetroid\Crateria\Central($this),
			'West Crateria' => new Region\Standard\SuperMetroid\Crateria\West($this),
			'East Crateria' => new Region\Standard\SuperMetroid\Crateria\East($this),

			'Green Brinstar' => new Region\Standard\SuperMetroid\Brinstar\Green($this),
			'Pink Brinstar' => new Region\Standard\SuperMetroid\Brinstar\Pink($this),
			'Blue Brinstar' => new Region\Standard\SuperMetroid\Brinstar\Blue($this),
			'Red Brinstar' => new Region\Standard\SuperMetroid\Brinstar\Red($this),
			'Kraids Lair Brinstar' => new Region\Standard\SuperMetroid\Brinstar\Kraid($this),

			'West Norfair' => new Region\Standard\SuperMetroid\Norfair\West($this),
			'East Norfair' => new Region\Standard\SuperMetroid\Norfair\East($this),
			'Crocomires Lair Norfair' => new Region\Standard\SuperMetroid\Norfair\Crocomire($this),

			'West Lower Norfair' => new Region\Standard\SuperMetroid\LowerNorfair\West($this),
			'East Lower Norfair' => new Region\Standard\SuperMetroid\LowerNorfair\East($this),

			'Wrecked Ship' => new Region\Standard\SuperMetroid\WreckedShip\WreckedShip($this),

			'Outer Maridia' => new Region\Standard\SuperMetroid\Maridia\Outer($this),
			'Inner Maridia' => new Region\Standard\SuperMetroid\Maridia\Inner($this),

			'Tourian' => new Region\Standard\SuperMetroid\Tourian\Tourian($this),
		];

		parent::__construct($difficulty, $logic, $goal, $variation);
	}
}
