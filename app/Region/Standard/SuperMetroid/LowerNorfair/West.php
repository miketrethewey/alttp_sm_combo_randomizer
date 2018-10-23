<?php namespace ALttP\Region\Standard\SuperMetroid\LowerNorfair;

use ALttP\Item;
use ALttP\Location;
use ALttP\Region;
use ALttP\Support\LocationCollection;
use ALttP\World;

/**
 * West Lower Norfair Region and it's Locations contained within
 */
class West extends Region {
	protected $name = 'Lower Norfair';

	/**
	 * Create a new Lower Norfair Region and initalize it's locations
	 *
	 * @param World $world World this Region is part of
	 *
	 * @return void
	 */
	public function __construct(World $world) {
		parent::__construct($world, 'SM');

		$this->locations = new LocationCollection([
            new Location\SuperMetroid\Visible("Missile (Gold Torizo)", 0xF78E6E, null, $this),
            new Location\SuperMetroid\Hidden("Super Missile (Gold Torizo)", 0xF78E74, null, $this),
            new Location\SuperMetroid\Chozo("Screw Attack", 0xF79110, null, $this),
		]);
	}

	/**
	 * Set Locations to have Items like the vanilla game.
	 *
	 * @return $this
	 */
	public function setVanilla() {
		$this->locations["Missile (Gold Torizo)"]->setItem(Item::get('Missile'));
		$this->locations["Super Missile (Gold Torizo)"]->setItem(Item::get('Super'));
		$this->locations["Screw Attack"]->setItem(Item::get('ScrewAttack'));
		return $this;
	}
	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Tournament
	 *
	 * @return $this
	 */
	public function initTournament() {
		$this->locations["Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			// a canFlySM check here is redundant, since with Space Jump you can fly, as is required to get this particular item
			return $items->canUsePowerbombs() && $items->has('SpaceJump') && $items->heatProof()
			&& ($items->has('HiJump') || $items->has('Gravity') || ($items->canAccessLowerNorfairPortal() && ($items->canSpringBallJump() || $items->has('SpeedBooster'))));
        });
        $this->locations["Super Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			return $items->canDestroyBombWalls() && $items-hasEnergyReserves(6);
        });
        $this->locations["Screw Attack"]->setRequirements(function($location, $items) {
			return $items->canDestroyBombWalls();
        });

        $this->can_enter = function($locations, $items) {
            return ($this->world->getRegion('East Norfair')->canEnter($locations, $items)
                && $items->canUsePowerBombs()
                && ($items->heatProof() && ($items->has('HiJump') || $items->has('Gravity'))))
                || ($items->canAccessLowerNorfairPortal() && $items->canDestroyBombWalls());
                // to follow the logic for this, Varia *can* be in Lower Norfair, but only if the game forces you to enter from the Misery Mire Swamp portal
        };
        
		return $this;
	}
	
	/**
	 * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
	 * within for Normal
	 *
	 * @return $this
	 */
	public function initNormal() {
		$this->locations["Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			return $items->canUsePowerbombs() && $items->has('SpaceJump') && $items->heatProof();
        });
        $this->locations["Super Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			return $items->canDestroyBombWalls() && ($items->hasEnergyReserves(8) || $items->has('Varia'));
        });
        $this->locations["Screw Attack"]->setRequirements(function($location, $items) {
			return $items->canDestroyBombWalls();
        });

        $this->can_enter = function($locations, $items) {
            return ($this->world->getRegion('East Norfair')->canEnter($locations, $items)
                && $items->canUsePowerBombs()
                && ($items->heatProof() && ($items->has('HiJump') || $items->has('Gravity'))))
                || ($items->canAccessLowerNorfairPortal() && $items->canDestroyBombWalls());
                // to follow the logic for this, Varia *can* be in Lower Norfair, but only if the game forces you to enter from the Misery Mire Swamp portal
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
		$this->locations["Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			return $items->canUsePowerbombs() && $items->has('SpaceJump') && $items->has('Super');
        });
        $this->locations["Super Missile (Gold Torizo)"]->setRequirements(function($location, $items) {
			return $items->canDestroyBombWalls() 
				&& ($items->canAccessLowerNorfairPortal() || ($items->has('SpaceJump') && $items->canUsePowerBombs()));
        });
        $this->locations["Screw Attack"]->setRequirements(function($location, $items) {
			return $this->can_enter($locations, $items) && $items->has('Charge') && $items->has('Ice') && $items->has('Wave') && $this->has('Spazer') && $items->has('Super', 2);
		});

        $this->can_enter = function($locations, $items) {
			return $items->heatProof()
			    && (($this->world->getRegion('East Norfair')->canEnter($locations, $items)
                && $items->canUsePowerBombs()
                && ($items->has('Gravity') && $items->has('SpaceJump')))
                || ($items->canAccessLowerNorfairPortal() && $items->canDestroyBombWalls()));					
        };
        
		return $this;
	}
}