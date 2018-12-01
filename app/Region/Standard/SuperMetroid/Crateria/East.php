<?php namespace ALttP\Region\Standard\SuperMetroid\Crateria;

use ALttP\Item;
use ALttP\Location;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * East Crateria Region and it's Locations contained within
 */
class East extends Region {
	protected $name = 'Crateria';

	/**
	 * Create a new Crateria Region and initalize it's locations
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world, 'SM');

		$this->locations = new LocationCollection([
            new Location\SuperMetroid\Visible("Missile (outside Wrecked Ship bottom)", 0xF781E8, null, $this),
            new Location\SuperMetroid\Hidden("Missile (outside Wrecked Ship top)", 0xF781EE, null, $this),
            new Location\SuperMetroid\Visible("Missile (outside Wrecked Ship middle)", 0xF781F4, null, $this),
            new Location\SuperMetroid\Visible("Missile (Crateria moat)", 0xF78248, null, $this),
		]);
	}

	/**
	 * Set Locations to have Items like the vanilla game.
	 *
	 * @return $this
	 */
	public function setVanilla() {
		$this->locations["Missile (outside Wrecked Ship bottom)"]->setItem(Item::get('Missile'));
		$this->locations["Missile (outside Wrecked Ship top)"]->setItem(Item::get('Missile'));
		$this->locations["Missile (outside Wrecked Ship middle)"]->setItem(Item::get('Missile'));
		$this->locations["Missile (Crateria moat)"]->setItem(Item::get('Missile'));
		return $this;
	}
	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Tournament Mode
	 *
	 * @return $this
	 */
	public function initTournament() {
		
		$this->locations["Missile (outside Wrecked Ship top)"]->setRequirements(function($location, $items) {
			return $items->has('Super') && $items->canPassBombPassages();				
		});
		$this->locations["Missile (outside Wrecked Ship middle)"]->setRequirements(function($location, $items) {
			return $items->has('Super') && $items->canPassBombPassages();				
		});
        $this->can_enter = function($locations, $items) {
			return ($items->canUsePowerBombs() && $items->has('Super'))
				|| ($items->canAccessNorfairPortal() && $items->canUsePowerBombs() && ($items->has('Ice') || $items->canSpringBallJump() || $items->has('HiJump') || $items->canFlySM()))
				|| ($items->canAccessMaridiaPortal() && $items->has('HiJump') && $items->has('Super'));
		};
		return $this;
	}
	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Normal Mode
	 *
	 * @return $this
	 */
	public function initNormal() {
		
		$this->locations["Missile (outside Wrecked Ship top)"]->setRequirements(function($location, $items) {
			return $items->has('Super') && $items->canPassBombPassages();				
		});
		$this->locations["Missile (outside Wrecked Ship middle)"]->setRequirements(function($location, $items) {
			return $items->has('Super') && $items->canPassBombPassages();
		});
        $this->can_enter = function($locations, $items) {
			return (($items->canUsePowerBombs() && $items->has('Super') && ($items->has('SpeedBooster') || $items->has('Grapple') || $items->has('SpaceJump') || ($items->has('Gravity') && ($items->has('HiJump') || $items->canIbj()))))  // if coming from Central/Landing Site
				|| ($items->canAccessNorfairPortal() && $items->canUsePowerBombs() && ($items->has('Ice') || $items->has('HiJump') || $items->canFlySM()))  // if coming from Red Tower in Brinstar
				|| ($items->canAccessMaridiaPortal() && $items->has('HiJump') && $items->has('Super')));  // if coming from Forgotten Highway, or through Maridia to the Landing Site
		};
		return $this;
	}
	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Casual Mode
	 *
	 * @return $this
	 */
	public function initCasual() {
		$this->locations["Missile (outside Wrecked Ship bottom)"]->setRequirements(function($location, $items) {
			return ($items->has('SpeedBooster') || $items->has('Grapple') || $items->has('SpaceJump') || $items->canSpringBallJump() || $items->canAccessMaridiaPortal());
		});
		$this->locations["Missile (outside Wrecked Ship top)"]->setRequirements(function($location, $items) {
			return $items->canEnterWreckedShipMain();
		});
		$this->locations["Missile (outside Wrecked Ship middle)"]->setRequirements(function($location, $items) {
			return $items->canEnterWreckedShipMain();
		});
		$this->can_enter = function($locations, $items) {
			return (($items->canUsePowerBombs() && $items->has('Super') && ($items->has('SpeedBooster') || $items->has('Grapple') || $items->has('SpaceJump') || ($items->has('Gravity') && $items->has('HiJump'))))  // if coming from Central/Landing Site
				|| ($items->canAccessNorfairPortal() && $items->canUsePowerBombs() && ($items->has('Ice') || $items->has('HiJump') || $items->has('SpaceJump')))
				|| ($items->canAccessMaridiaPortal() && $items->has('Gravity') && $items->has('Super')));
		};
		return $this;
	}
}