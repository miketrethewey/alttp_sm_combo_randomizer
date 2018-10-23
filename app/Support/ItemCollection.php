<?php namespace ALttP\Support;

use ALttP\Item;
use ALttP\World;
use ArrayIterator;

/**
 * Collection of Items, maintains counts of items collected as well.
 */
class ItemCollection extends Collection {
	protected $item_counts = [];
	protected $world;
	private $string_rep = null;

	/**
	 * Create a new collection.
	 *
	 * @param mixed $items
	 * @param World $world if this is related to a world for config
	 *
	 * @return void
	 */
	public function __construct($items = [], World $world = null) {
		foreach ($this->getArrayableItems($items) as $item) {
			$this->addItem($item);
		}
		$this->world = $world ?? new class extends World {
			public function __construct() {}
			public function config(string $key, $default = NULL) { return null; }
		};
	}

	/**
	 * Add an Item to this Collection
	 *
	 * @param Item $item
	 *
	 * @return $this
	 */
	public function addItem(Item $item) {
		$item_name = $item->getName();
		$this->offsetSet($item_name, $item);
		if (!isset($this->item_counts[$item_name])) {
			$this->item_counts[$item_name] = 0;
		}

		$this->item_counts[$item_name]++;
		$this->string_rep = null;

		return $this;
	}

	/**
	 * Remove an item from the collection by name.
	 *
	 * @return $this
	 */
	public function removeItem($name) {
		if (!isset($this->item_counts[$name])) {
			return $this;
		}

		$this->item_counts[$name]--;
		if ($this->item_counts[$name] === 0) {
			$this->offsetUnset($name);
		}

		return $this;
	}

	/**
	 * Run a filter over each of the items.
	 *
	 * @param callable|null $callback
	 *
	 * @return static
	 */
	public function filter(callable $callback = null) {
		if ($callback) {
			return new static(array_filter($this->values(), $callback), $this->world);
		}

		return new static(array_filter($this->values()), $this->world);
	}

	/**
	 * Get an array of the underlying elements
	 *
	 * @return array
	 */
	public function values() {
		$values = [];
		foreach ($this->items as $item) {
			for ($i = 0; $i < $this->item_counts[$item->getName()]; $i++) {
				$values[] = $item;
			}
		}
		return $values;
	}

	/**
	 * Get the items in the collection that are not present in the given items.
	 *
	 * @param mixed $items items to diff against
	 *
	 * @return static
	 */
	public function diff($items) {
		if (!count($items)) {
			return $this->copy();
		}

		// TODO: this might not be correct
		if (!is_a($items, static::class)) {
			return parent::diff($items);
		}

		$diffed = $this->copy();

		foreach ($diffed->item_counts as $name => $amount) {
			if (isset($items->item_counts[$name])) {
				if ($items->item_counts[$name] < $amount) {
					$diffed->item_counts[$name] = $amount - $items->item_counts[$name];
				} else {
					$diffed->offsetUnset($name);
				}
			}
		}
		return $diffed;
	}

	/**
	 * Intersect the collection with the given items.
	 *
	 * @param  mixed  $items
	 *
	 * @return static
	 */
	public function intersect($items) {
		return new static(array_intersect($this->items, $this->getArrayableItems($items)), $this->world);
	}

	/**
	 * Execute a callback over each item.
	 *
	 * @param callable $callback
	 *
	 * @return $this
	 */
	public function each(callable $callback) {
		foreach ($this->items as $key => $item) {
			for ($i = 0; $i < $this->item_counts[$key]; $i++) {
				if ($callback($item, $key) === false) {
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * Merge the collection with the given items.
	 *
	 * @TODO: this whole function may be incorrect
	 *
	 * @param mixed $items
	 *
	 * @return static
	 */
	public function merge($items) {
		if (!count($items)) {
			return $this->copy();
		}

		if (!$items instanceof static) {
			return $this->merge(new static($items, $this->world));
		}

		$merged = $this->copy();

		$items->each(function($item) use ($merged) {
			$merged->addItem($item);
		});

		return $merged;
	}

	/**
	 * Get a fresh copy of this object, the underlying items will still be the same
	 *
	 * @return static
	 */
	public function copy() {
		$new = new static([], $this->world);
		$new->items = $this->items;
		$new->item_counts = $this->item_counts;

		return $new;
	}

	/**
	 * Reduce the collection to a single value.
	 *
	 * @param callable $callback
	 * @param mixed $initial
	 *
	 * @return mixed
	 */
	public function reduce(callable $callback, $initial = null) {
		return array_reduce($this->values(), $callback, $initial);
	}

	/**
	 * Run a map over each of the items.
	 *
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function map(callable $callback) {
		return array_map($callback, $this->values());
	}

	/**
	 * Determine if an item exists in the collection by key.
	 *
	 * @param mixed $key
	 * @param int $at_least mininum number of item in collection
	 *
	 * @return bool
	 */
	public function has($key, $at_least = 1) {
		if ($at_least === 0) {
			return true;
		}

		if ($at_least == null) {
			return false;
		}

		// @TODO: this check is expensive, as this function is called A LOT, can we reduce it somehow?
		if ($this->world->config('rom.genericKeys', false) && ($this->item_counts['ShopKey'] ?? false)
			&& strpos($key, 'Key') === 0) {
			return true;
		}

		return ($this->item_counts[$key] ?? 0) >= $at_least;
	}

	/**
	 * For testing, we up the key count to 10 for every dungeon.
	 *
	 * @return $this
	 */
	public function manyKeys() : self {
		foreach ($this->item_counts as $key => $count) {
			if (strpos($key, 'Key') === 0) {
				$this->item_counts[$key] = 10;
			}
		}

		return $this;
	}

	/**
	 * Get the collection of items as a plain array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_map(function ($value) {
			return $value instanceof Arrayable ? $value->toArray() : $value;
		}, $this->values());
	}

	/**
	 * Count the number of items in the collection.
	 *
	 * @return int
	 */
	public function count() {
		return array_sum($this->item_counts);
	}

	/**
	 * Get an iterator for the items.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator($this->toArray());
	}

	/**
	 * Count the number of an item in the collection.
	 *
	 * @param mixed $key
	 *
	 * @return int
	 */
	public function countItem($key) {
		return $this->item_counts[$key] ?? 0;
	}

	/**
	 * Unset the item at a given offset.
	 *
	 * @param mixed $offset
	 *
	 * @return void
	 */
	public function offsetUnset($offset) {
		unset($this->item_counts[$offset]);
		unset($this->items[$offset]);
	}

	/**
	 * Add an Item to a copy of this Collection
	 *
	 * @param Item $item
	 *
	 * @return static
	 */
	public function tempAdd(Item $item) {
		$temp = $this->copy();
		return $temp->addItem($item);
	}

	/**
	 * Get total collectable Health
	 *
	 * @param float $initial starting health
	 *
	 * @return float
	 */
	public function heartCount($initial = 3) {
		$count = $initial;

		$hearts = $this->filter(function($item) {
			return $item instanceof Item\Upgrade\Health;
		});

		foreach ($hearts as $heart) {
			$count += ($heart->getName() == 'PieceOfHeart') ? .25 : 1;
		}

		return $count;
	}

	public function canActivateMedallions()
	{
		return $this->hasSword() || ($this->world->config('mode.weapons') == 'swordless' && $this->has('Hammer'));
	}

	public function canActivateTablets()
	{
		return $this->canRead() && ($this->hasSword(2) || ($this->world->config('mode.weapons') == 'swordless' && $this->has('Hammer')));
	}

	public function canDash()
	{
		return $this->has('PegasusBoots');
	}

	public function canGrapple()
	{
		return $this->has('Hookshot');
	}

	/**
	 * Requirements for lifting rocks
	 *
	 * @return bool
	 */
	public function canLiftRocks() {
		return $this->has('PowerGlove')
			|| $this->has('ProgressiveGlove')
			|| $this->has('TitansMitt');
	}

	/**
	 * Requirements for lifting dark rocks
	 *
	 * @return bool
	 */
	public function canLiftDarkRocks() {
		return $this->has('TitansMitt')
			|| $this->has('ProgressiveGlove', 2);
	}

	/**
	 * Requirements for lighting torches
	 *
	 * @return bool
	 */
	public function canLightTorches() {
		return $this->has('FireRod') || $this->has('Lamp');
	}

	/**
	 * Requirements for melting things, like ice statues
	 * should only be used in places where we have put Bombos pads in swordless
	 *
	 * @return bool
	 */
	public function canMeltThings() {
		return $this->has('FireRod')
			|| ($this->has('Bombos') && ($this->world->config('mode.weapons') == 'swordless' || $this->hasSword()));
	}

	/**
	 * Requirements for fast travel through the duck
	 *
	 * @return bool
	 */
	public function canFly() {
		if ($this->world instanceof World\Inverted
			&& !($this->has('MoonPearl') && $this->world->getRegion('North West Light World')->canEnter([], $this))) {
			return false;
		}
		return $this->has('OcarinaActive') || $this->has('OcarinaInactive');
	}

	public function canRead()
	{
		return $this->has('BookOfMudora');
	}

	/**
	 * Requirements for fast travel through the spin/hook speed
	 *
	 * @return bool
	 */
	public function canSpinSpeed() {
		return $this->canDash()
			&& ($this->hasSword() || $this->canGrapple());
	}

	/**
	 * Requirements for lobbing arrows at things
	 *
	 * @param int $min_level minimum level of bow
	 *
	 * @return bool
	 */
	public function canShootArrows(int $min_level = 1) {
		switch ($min_level) {
			case 2:
				return $this->has('BowAndSilverArrows')
					|| ($this->has('SilverArrowUpgrade')
						&& ($this->has('Bow') || $this->has('BowAndArrows')));
			case 1:
			default:
				return ($this->has('Bow')
						&& (!$this->world->config('rom.rupeeBow', false) || $this->has('ShopArrow') || $this->has('SilverArrowUpgrade')))
					|| $this->has('BowAndArrows')
					|| $this->has('BowAndSilverArrows');
		}

	}

	public function canSwim()
	{
		return $this->has('Flippers');
	}

	/**
	 * Requirements for blocking lasers
	 *
	 * @return bool
	 */
	public function canBlockLasers() {
		return $this->has('MirrorShield')
			|| $this->has('ProgressiveShield', 3);
	}

	/**
	 * Requirements for blocking lasers
	 *
	 * @return bool
	 */
	public function canExtendMagic($bars = 2) {
		return ($this->has('HalfMagic') ? 2 : 1)
			* ($this->has('QuarterMagic') ? 4 : 1)
			* ($this->bottleCount() + 1) >= $bars;
	}

	/**
	 * Requirements for being link in Dark World using Major Glitches
	 *
	 * @return bool
	 */
	public function glitchedLinkInDarkWorld() {
		return $this->has('MoonPearl')
			|| $this->hasABottle();
	}

	/**
	 * Requirements for killing most things
	 *
	 * @return bool
	 */
	public function canKillMostThings($enemies = 5) {
		return $this->has('UncleSword')
			|| (!$this->world->getCurrentlyFillingItems()->count() && $this->hasSword())
			|| $this->has('CaneOfSomaria')
			|| ($this->has('TenBombs') && $enemies < 6)
			|| ($this->has('CaneOfByrna') && ($enemies < 6 || $this->canExtendMagic()))
			|| $this->canShootArrows()
			|| $this->has('Hammer')
			|| $this->has('FireRod');
	}

	/**
	 * Requirements for bombing things
	 *
	 * @return bool
	 */
	public function canBombThings() {
		return true;
	}

	/**
	 * Requirements for catching a Golden Bee
	 *
	 * @return bool
	 */
	public function canGetGoodBee() {
		return $this->has('BugCatchingNet')
			&& $this->hasABottle()
			&& ($this->canDash()
				|| ($this->hasSword() && $this->has('Quake')));
	}

	/**
	 * Requirements for having a sword, we treat the special UncleSword like a progressive sword.
	 *
	 * @param int $min_level minimum level of sword
	 *
	 * @return bool
	 */
	public function hasSword(int $min_level = 1) {
		switch ($min_level) {
			case 4:
				return $this->has('ProgressiveSword', 4)
					|| $this->has('UncleSword') && $this->has('ProgressiveSword', 3)
					|| $this->has('L4Sword');
			case 3:
				return $this->has('ProgressiveSword', 3)
					|| $this->has('UncleSword') && $this->has('ProgressiveSword', 2)
					|| $this->has('L3Sword')
					|| $this->has('L4Sword');
			case 2:
				return $this->has('ProgressiveSword', 2)
					|| $this->has('UncleSword') && $this->has('ProgressiveSword')
					|| $this->has('L2Sword')
					|| $this->has('MasterSword')
					|| $this->has('L3Sword')
					|| $this->has('L4Sword');
			case 1:
			default:
				return $this->has('ProgressiveSword')
					|| $this->has('UncleSword')
					|| $this->has('L1Sword')
					|| $this->has('L1SwordAndShield')
					|| $this->has('L2Sword')
					|| $this->has('MasterSword')
					|| $this->has('L3Sword')
					|| $this->has('L4Sword');
		}
	}

	/**
	 * Requirements for having X bottles
	 *
	 * @param int $at_least mininum number of item in collection
	 *
	 * @return bool
	 */
	public function hasBottle(int $at_least = 1) : bool {
		return $this->bottleCount() >= $at_least;
	}

	/**
	 * Requirements for having X bottles
	 *
	 * @param int $at_least mininum number of item in collection
	 *
	 * @return bool
	 */
	public function bottleCount() : int {
		return $this->filter(function($item) {
			return $item instanceof Item\Bottle;
		})->count();
	}

	/**
	 * Requirements for having a bottle
	 *
	 * @return bool
	 */
	public function hasABottle() {
		return $this->has('BottleWithBee')
			|| $this->has('BottleWithFairy')
			|| $this->has('BottleWithRedPotion')
			|| $this->has('BottleWithGreenPotion')
			|| $this->has('BottleWithBluePotion')
			|| $this->has('Bottle')
			|| $this->has('BottleWithGoldBee');
	}

 	/**
 	*  Requirements for accessing the Portal location in Upper Norfair, which goes to Death Mountain
 	*
 	* @return bool
 	*/
 	public function canAccessDeathMountainPortal()
 	{
 		return ($this->canDestroyBombWalls() || $this->has('SpeedBooster')) && ($this->has('Super') && $this->has('Morph'));
 	}
 	
 	/** Requirements for accessing the Death Mountain portal location, which goes to Upper Norfair
 	*
 	* @return bool
 	*/
 	public function canAccessNorfairPortal()
 	{
 		return $this->canFly() || ($this->canLiftRocks() && $this->has('Lamp'));
 	}
 	
 	/** Requirements for accessing the Misery Mire Portal location, which goes to the Screw Attack in Lower Norfair
 	*
 	* @return bool
 	*/
 	public function canAccessLowerNorfairPortal()
 	{
 		switch ($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->canFly() && $this->canLiftDarkRocks() && $this->has('MoonPearl');  // only require Moon Pearl for Mire Swamp, for instances of Murderdactyles
 			default:
 				return $this->canFly() && $this->canLiftDarkRocks();
 		}
 	}
 	
 	/** Requirements for accessing the Lower Norfair Portal location, which goes to the swamp in Misery Mire
 	*
 	*   Since we're coming from Upper Norfair, a lava dive will *always* require Varia Suit to be present before you are assumed to go to Lower Norfair
 	* @return bool
 	*/
 	public function canAccessMiseryMirePortal()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->has('Varia') && $items->has('Charge') && $this->has('Super', 4) && $this->canUsePowerBombs() && $this->has('Gravity')
 				&& $this->has('SpaceJump') && $items->has('Ice') && $items->has('Spazer') && $items->has('Wave') && $items->has('PowerBomb', 3);
 				// Casual logic will dictate that we need to fight Golden Torizo, so a minimum of 20 Supers and Charge/Ice/Spazer/Wave will be needed for the fight,
 				// as well as 15 PBs to get around all of the bombable blocks in Lower Norfair
 			case 'Normal':
 				return $items->has('Varia') && $items->hasEnergyReserves(3) && $items->has('Super', 2) && $items->canUsePowerBombs() && ($this->has('HiJump') || $this->has('Gravity'));
 			case 'Tournament':
 			default:
 				return $items->has('Varia') && $items->hasEnergyReserves(2) && $items->has('Super') && $items->canUsePowerBombs() && ($this->has('HiJump') || $this->has('Gravity'));
 		}
 	}
 	
 	/** Requirements for accessing the Dark Lake Hylia Portal location, which goes to Eastern Maridia near Draygon
 	*
 	* @return bool
 	*/
 	public function canAccessMaridiaPortal()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->has('MoonPearl')
 					&& $this->has('RescueZelda')
 					&& $this->has('Flippers')
 					&& $this->has('Gravity')
 					&& $this->has('Morph')
 					&& ($this->has('DefeatAgahnim')
 						|| ($this->has('Hammer') && $this->canLiftRocks())
 						|| $this->canLiftDarkRocks());
 			case 'Normal':
 				return $this->has('MoonPearl')
 					&& $this->has('RescueZelda')
 					&& $this->has('Flippers')
 					&& $this->has('Morph')
 					&& ($this->has('HiJump') && $item->has('Grapple'))
 					&& ($this->has('DefeatAgahnim')
 						|| ($this->has('Hammer') && $this->canLiftRocks())
 						|| $this->canLiftDarkRocks());					
 			case 'Tournament':
 			default:
 				return $this->has('MoonPearl')
 					&& $this->has('RescueZelda')
 					&& $this->has('Flippers')
 					&& $this->has('Morph')
 					&& ($this->canSpringBallJump() || $this->has('HiJump'))
 					&& ($this->has('DefeatAgahnim')
 						|| ($this->has('Hammer') && $this->canLiftRocks())
 						|| $this->canLiftDarkRocks());
 				}
 	}
 	
 	/** Requirements for accessing the Eastern Maridia Portal location, which goes to Lake Hylia in southeastern Dark Hyrule
 	*
 	* @return bool
 	*/
 	public function canAccessDarkWorldPortal()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->canUsePowerBombs() && $this->has('Charge') && $this->has('PowerBomb', 3) && $this->has('Super') && $this->has('Gravity') && $this->has('SpeedBooster') && $this->canFlySM() && $this->has('MoonPearl')&& ($items->has('Flippers') || $items->has('MagicMirror'));
 			case 'Tournament':
 			case 'Normal':
 				return $this->canUsePowerBombs() && $this->has('MoonPearl') && ($items->has('Flippers') || $items->has('MagicMirror'))
 					&& $this->has('Super', 2) && $this->has('Missile', 4)
 					&& ($this->has('Gravity') || ($this->has('HiJump') && $this->has('Ice') && $this->has('Grapple')))
 					&& ($this->has('Ice') || ($this->has('SpeedBooster') && $this->has('Gravity')));
 		}
 	}
 	
 	/** Requirements for entering the Wrecked Ship, either through the front door or through Forgotten Highway
 	*
 	* @return bool
 	*/
 	public function canEnterWreckedShipMain()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $items->canUsePowerBombs() && $items->has('PowerBomb', 2) && $this->has('Super', 2) && $this->has('Missile', 5)
 				&& (($this->hasEnergyReserves(5) || ($this->hasEnergyReserves(3) && $this->has('Varia'))) && ($items->has('SpeedBooster') || $items->has('Grapple') || $items->has('SpaceJump') || $items->canSpringBallJump()));
 			case 'Normal':
 			case 'Tournament':
 			default:
 				return $this->has('Super') && $this->has('Missile', 2)
 				&& (($items->canUsePowerBombs() && $this->hasEnergyReserves(3) && ($items->has('SpeedBooster') || $items->has('Grapple') || $items->canSpringBallJump()))
 				|| ($items->hasEnergyReserves(2) && $items->canAccessMaridiaPortal() && $items->canPassBombPassages()));
 		}
 	}
 	
 	/** Requirements for entering and leaving the Gauntlet in Crateria
 	*
 	* @return bool
 	*/
 	public function canEnterAndLeaveGauntlet()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return ($this->has('Morph') && ($this->canFlySM() || $this->has('SpeedBooster')))
 					&& ($this->canIbj()
 						|| ($this->canUsePowerBombs() && $this->has('PowerBomb', 2))
 						|| $this->has('ScrewAttack'));
 			case 'Tournament':
 			case 'Normal':
 			default:
 				return ($this->has('Morph') && ($this->has('Bombs') || $this->has('PowerBomb', 2)))
 					 || $this->has('ScrewAttack')
 					 || ($this->has('SpeedBooster') && $this->canUsePowerBombs() && $this->has('PowerBomb', 2) && $this->hasEnergyReserves(2));
 		}		
 	}
 	
 	/* Requirements for being able to get to Crocomire and exit his area
 	* We will account for coming from both sides, which will be clearly spelled out below
 	* v6.1 of Croc logic
 	* Morphless Croc involves going through Worst Room in the Game, up through Mickey Mouse, and then back to the elevator to get to Lava Dive, which then goes to either the grapple maw room, or Bubble Mountain to the blue gate
 	* Obvioulsy that is a long trek, so a minimum of 7 energy tanks is required to progress that way, if it involves Crystal Flash
 	*
 	* @return bool
 	*/
 	public function canEnterAndLeaveCrocomire()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $items->has('Wave') && $items->has('Super') && $items->has('Varia') && $items->has('Charge')
 				&& ((($items->canIbj() || $items->has('HiJump') || $items->has('Ice') || $items->has('SpaceJump'))   // this one takes us through Cathedral and Bubble Mountain to the blue gate to Croc
 				   || ($items->has('SpeedBooster') || $items->canUsePowerBombs())));  // this one takes us through either the Super Door to the Croc speedway, or the speedway to the blue gate next to Croc
 			// Lower Norair's portal is not considered on Casual
 			case 'Normal':
 				return $items->has('Super', 2) && $items->has('Missile', 3)
 				&& (((($items->hasEnergyReserves(5) || ($items->hasEnergyReserves(2) && $items->canCrystalFlash())) || ($items->has('Varia') && $items-hasEnergyReserves(2)))
 				   && $items->canPassBombPassages() && ($items->canIbj() || $items->has('HiJump') || $items->has('Ice')))  // this takes us through Cathedral and Bubble Mountain
 				   || ($items->has('SpeedBooster') && $items->canUsePowerBombs() && $items->hasEnergyReserves(2))  // this takes us through the Croc speedway using the Ice Super Door, or the speedway to Bubble Mountain
 				   || ($items->canAccessLowerNorfairPortal() && $items->canFlySM() && $items->canDestroyBombWalls() && ((($items-hasEnergyReserves(8) && $items->canCrystalFlash()) || ($items->hasEnergyReserves(2) && $items->has('Varia'))))));  // this one takes us through the Misery Mire portal, back through the lava dive, and to the blue gate to Croc
 			case 'Tournament':
 			default:
 				return $items->has('Super', 2)
 				&& (((($items->hasEnergyReserves(3) || ($items->hasEnergyReserves(2) && $items->canCrystalFlash())) || ($items->has('Varia') && $items-hasEnergyReserves(1)))
 				   && $items->canPassBombPassages() && ($items->canIbj() || $items->has('HiJump') || $items->has('Ice') || $items->canSpringBallJump()))  // this takes us through Cathedral and Bubble Mountain
 				   || ($items->has('SpeedBooster') && $items->canUsePowerBombs() && $items->hasEnergyReserves(2))  // this takes us through the Croc speedway using the Ice Super Door, or the speedway to Bubble Mountain
 				   || ($items->canAccessLowerNorfairPortal() && $items->canDestroyBombWalls() && ($items->canSpringBallJump() || $items->canFlySM()) && ((($items-hasEnergyReserves(7) && $items->canCrystalFlash()) || ($items->hasEnergyReserves(2) && $items->has('Varia'))))));  // this one takes us through the Misery Mire portal, back through the lava dive, and to the blue gate to Croc
 		}
 	}
 	public function canDefeatBotwoon()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->has('SpeedBooster') || $this->canAccessMaridiaPortal();
 			case 'Tournament':
 			case 'Normal':
 			default:
 				return $this->has('Ice') || $this->has('SpeedBooster') || $this->canAccessMaridiaPortal();
 		}
 	}
 	public function canDefeatDraygon()
 	{
 		switch($this->world->getSMLogic())
 		{
 			case 'Casual':
 				return $this->canDefeatBotwoon() && $this->has('Gravity') && (($this->has('SpeedBooster') && $this->has('HiJump')) || $this->canFlySM()) && $items->has('Super');
 			case 'Tournament':
 				return $items->has('Super') && ($this->canDefeatBotwoon() || $this->canAccessMaridiaPortal()) && $this->has('Grapple') && $items->has('Morph');
 			case 'Normal':
 				return $items->has('Super') && ($this->canDefeatBotwoon() || $this->canAccessMaridiaPortal()) && $this->has('Gravity');
 			default:
 				return $this->canDefeatBotwoon() && $this->has('Gravity');
 		}
 	}
 	
 	/*
 	* Determine if any SM progression can be in Ganon's Tower, and allow if so
 	
 	* @return bool
 	*/
 	public function canProgressInGT()
 	{
 
 	}
 	
	/* Super Metroid Ability Macros */
	public function canBombThingsSM()
	{
		return $this->canUseMorphBombs() || $this->canUsePowerBombs();
	}
	public function canIbj() {
		return $this->canUseMorphBombs();
	}
	public function canFlySM() {
		return $this->canIbj() || $this->has('SpaceJump');
	}
	public function canCrystalFlash()
	{
		return $this->has('Missile', 2)
			&& $this->has('Super', 2)
			&& $this->has('PowerBomb', 3)
			&& $this->canMorph();
	}
	// Not having morph in logic for Crystal flash could create a very crazy edge-case where CF is needed in Norfair,
	// but Morph is in Bubble Mountain for example, and the game only gave you 2 tanks for the heat run.
	public function hasEnergyReserves(int $amount = 0)
	{
		return (($this->countItem('ETank') + $this->countItem('ReserveTank')) >= $amount);
	}
	public function canDashSM()
	{
		return $this->has('SpeedBooster');
	}
	public function canGrappleSM()
	{
		return $this->has('Grapple');
	}
	public function heatProof()
	{
		return $this->has('Varia');
	}
	public function canHellRun(int $amount = 5)
	{
		return $this->heatProof() || $this->hasEnergyReserves($amount);
	}
	public function canHiJump()
	{
		return $this->has('HiJump');
	}
	public function canMorph()
	{
		return $this->has('Morph');
	}
	public function canUseMorphBombs()
	{
		return $this->canMorph() && $this->has('Bombs');
	}
	public function canUsePowerBombs()
	{
		return $this->canMorph() && $this->has('PowerBomb');
	}
	public function canOpenGreenDoors()
	{
		return $this->has('Super');
	}
	public function canOpenRedDoors()
	{
		return $this->has('Missile') || $this->canOpenGreenDoors();
	}
	public function canOpenYellowDoors()
	{
		return $this->canUsePowerBombs();
	}
	public function canDestroyBombWalls()
	{
		return ($this->canUseMorphBombs() || $this->canUsePowerBombs())
			|| $this->has('ScrewAttack');
	}
	public function canSpringBallJump()
	{
		return $this->canMorph() && $this->has('SpringBall');
	}
	public function canSwimSM()
	{
		return $this->has('Gravity');
	}
	public function canEnterAndLeaveGauntlet()
	{
		switch($this->world->getSMLogic())
		{
			case 'Casual':
				return ($this->canMorph() && ($this->canFlySM() || $this->canDashSM()))
					&& ($this->canIbj()
						|| ($this->canUsePowerBombs() && $this->has('PowerBomb', 2))
						|| $this->has('ScrewAttack'));
			case 'Normal':
			case 'Tournament':
			default:
				return ($this->canMorph() && ($this->canUseMorphBombs() || $this->has('PowerBomb', 2)))
					 || $this->has('ScrewAttack')
					 || ($this->canDashSM() && $this->canUsePowerBombs() && $this->hasEnergyReserves(2));
		}
	}
	public function canPassBombPassages()
	{
		return $this->canUsePowerBombs() || $this->canIbj();
	}

	public function __toString() {
		if ($this->string_rep === null) {
			$this->string_rep = $this->reduce(function($carry, $item) {
				return $carry . $item->getName();
			}, '');
		}

		return $this->string_rep;
	}
}

