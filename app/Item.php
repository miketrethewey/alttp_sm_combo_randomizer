<?php namespace ALttP;

use ALttP\Support\ItemCollection;

/**
 * An Item is any collectable thing in game.
 */
class Item {
	protected $bytes;
	protected $address;
	protected $name;
	protected $nice_name;
	protected $linked_region;

	static protected $items;

	/**
	 * Get the Item by name
	 *
	 * @param string $name Name of Item
	 *
	 * @throws Exception if the Item doesn't exist
	 *
	 * @return Item
	 */
	static public function get(string $name) {
		$items = static::all();
		if (isset($items[$name])) {
			return $items[$name];
		}

		return static::getNice($name);
	}

	/**
	 * Get the Item by nice name
	 *
	 * @param string $name Name of Item
	 *
	 * @throws Exception if the Item doesn't exist
	 *
	 * @return Item
	 */
	static public function getNice(string $name) {
		$items = static::all();

		foreach ($items as $item) {
			if ($item->getNiceName() == $name) {
				return $item;
			}
		}

		throw new \Exception('Unknown Item: ' . $name);
	}

	/**
	 * Get the Item by byte
	 *
	 * @param int $byte byte of Item
	 *
	 * @throws Exception if the Item doesn't exist
	 *
	 * @return Item
	 */
	static public function getWithByte(int $byte) {
		foreach (static::all() as $item) {
			if ($item->bytes[0] == $byte) {
				return $item;
			}
		}

		throw new \Exception('Unknown Item with byte: ' . $byte);
	}

	/**
	 * Get the Item by bytes
	 *
	 * @param array $bytes array of bytes of Item
	 *
	 * @throws Exception if the Item doesn't exist
	 *
	 * @return Item
	 */
	static public function getWithBytes(array $bytes) {
		foreach (static::all() as $item) {
			foreach ($bytes as $key => $byte) {
				if (!isset($item->bytes[$key]) || $item->bytes[$key] != $byte) {
					continue 2;
				}
			}
			return $item;
		}

		throw new \Exception('Unknown Item with bytes: ' . json_encode($bytes));
	}

	/**
	 * Get the all known Items
	 *
	 * @return ItemCollection
	 */
	static public function all() {
		if (static::$items) {
			return static::$items;
		}

		static::$items = new ItemCollection([
			new Item('Nothing', __('item.Nothing'), [0x5A]),
			new Item\Sword('L1Sword', __('item.L1Sword'), [0x49], null, 0xf06c, 0xe8 + 0xf06c, 0x1d0 + 0xf06c), // Uncle must be dead
			new Item\Sword('L1SwordAndShield', __('item.L1SwordAndShield'), [0x00], null, 0xf06c, 0xe8 + 0xf06c, 0x1d0 + 0xf06c), // Uncle must be dead
			new Item\Sword('L2Sword', __('item.L2Sword'), [0x01], null, 0xf06c, 0xe8 + 0xf06c, 0x1d0 + 0xf06c),
			new Item\Sword('MasterSword', __('item.MasterSword'), [0x50], null, 0xf06c, 0xe8 + 0xf06c, 0x1d0 + 0xf06c),
			new Item\Sword('L3Sword', __('item.L3Sword'), [0x02], null, 0xf070, 0xe8 + 0xf070, 0x1d0 + 0xf070), // Uncle must be dead
			new Item\Sword('L4Sword', __('item.L4Sword'), [0x03], null, 0xf074, 0xe8 + 0xf074, 0x1d0 + 0xf074), // Uncle must be dead
			new Item\Shield('BlueShield', __('item.BlueShield'), [0x04], null, 0xf080, 0xe8 + 0xf080, 0x1d0 + 0xf080), // Uncle must be dead
			new Item\Shield('RedShield', __('item.RedShield'), [0x05], null, 0xf084, 0xe8 + 0xf084, 0x1d0 + 0xf084), // Uncle must be dead
			new Item\Shield('MirrorShield', __('item.MirrorShield'), [0x06], null, 0xf088, 0xe8 + 0xf088, 0x1d0 + 0xf088), // Uncle must be dead
			new Item('FireRod', __('item.FireRod'), [0x07], null, 0xf000, 0xe8 + 0xf000, 0x1d0 + 0xf000),
			new Item('IceRod', __('item.IceRod'), [0x08], null, 0xf004, 0xe8 + 0xf004, 0x1d0 + 0xf004),
			new Item('Hammer', __('item.Hammer'), [0x09], null, 0xf018, 0xe8 + 0xf018, 0x1d0 + 0xf018),
			new Item('Hookshot', __('item.Hookshot'), [0x0A], null, 0xeff0, 0xe8 + 0xeff0, 0x1d0 + 0xeff0),
			new Item\Bow('Bow', __('item.Bow'), [0x0B], null, 0xefe0, 0xe8 + 0xefe0, 0x1d0 + 0xefe0),
			new Item('Boomerang', __('item.Boomerang'), [0x0C], null, 0xefe8, 0xe8 + 0xefe8, 0x1d0 + 0xefe8),
			new Item('Powder', __('item.Powder'), [0x0D], null, 0xeffc, 0xe8 + 0xeffc, 0x1d0 + 0xeffc),
			new Item\BottleContents('Bee', __('item.Bee'), [0x0E], null, 0xefe0, 0xe8 + 0xefe0, 0x1d0 + 0xefe0),
			new Item\Medallion('Bombos', __('item.Bombos'), [0x0f, 0x00, 't0' => 0x51, 't1' => 0x10, 't2' => 0x00, 'm0' => 0x51, 'm1' => 0x00, 'm2' => 0x00], null, 0xf008, 0xe8 + 0xf008, 0x1d0 + 0xf008),
			new Item\Medallion('Ether', __('item.Ether'), [0x10, 0x01, 't0' => 0x51, 't1' => 0x18, 't2' => 0x00, 'm0' => 0x13, 'm1' => 0x9F, 'm2' => 0xF1], null, 0xf00c, 0xe8 + 0xf00c, 0x1d0 + 0xf00c),
			new Item\Medallion('Quake', __('item.Quake'), [0x11, 0x02, 't0' => 0x14, 't1' => 0xEF, 't2' => 0xC4, 'm0' => 0x51, 'm1' => 0x08, 'm2' => 0x00], null, 0xf010, 0xe8 + 0xf010, 0x1d0 + 0xf010),
			new Item('Lamp', __('item.Lamp'), [0x12], null, 0xf014, 0xe8 + 0xf014, 0x1d0 + 0xf014),
			new Item('Shovel', __('item.Shovel'), [0x13], null, 0xf01c, 0xe8 + 0xf01c, 0x1d0 + 0xf01c),
			new Item('OcarinaInactive', __('item.OcarinaInactive'), [0x14], null, 0xf020, 0xe8 + 0xf020, 0x1d0 + 0xf020),
			new Item('CaneOfSomaria', __('item.CaneOfSomaria'), [0x15], null, 0xf048, 0xe8 + 0xf048, 0x1d0 + 0xf048),
			new Item\Bottle('Bottle', __('item.Bottle'), [0x16], null, 0xf02c, 0xe8 + 0xf02c, 0x1d0 + 0xf02c),
			new Item\Upgrade\Health('PieceOfHeart', __('item.PieceOfHeart'), [0x17], null, 0xf08c, 0xe8 + 0xf08c, 0x1d0 + 0xf08c),
			new Item('CaneOfByrna', __('item.CaneOfByrna'), [0x18], null, 0xf04c, 0xe8 + 0xf04c, 0x1d0 + 0xf04c),
			new Item('Cape', __('item.Cape'), [0x19], null, 0xf050, 0xe8 + 0xf050, 0x1d0 + 0xf050),
			new Item('MagicMirror', __('item.MagicMirror'), [0x1A], null, 0xf054, 0xe8 + 0xf054, 0x1d0 + 0xf054),
			new Item('PowerGlove', __('item.PowerGlove'), [0x1B], null, 0xf058, 0xe8 + 0xf058, 0x1d0 + 0xf058),
			new Item('TitansMitt', __('item.TitansMitt'), [0x1C], null, 0xf05c, 0xe8 + 0xf05c, 0x1d0 + 0xf05c),
			new Item('BookOfMudora', __('item.BookOfMudora'), [0x1D], null, 0xf028, 0xe8 + 0xf028, 0x1d0 + 0xf028),
			new Item('Flippers', __('item.Flippers'), [0x1E], null, 0xf064, 0xe8 + 0xf064, 0x1d0 + 0xf064),
			new Item('MoonPearl', __('item.MoonPearl'), [0x1F], null, 0xf068, 0xe8 + 0xf068, 0x1d0 + 0xf068),
			new Item('BugCatchingNet', __('item.BugCatchingNet'), [0x21], null, 0xf024, 0xe8 + 0xf024, 0x1d0 + 0xf024),
			new Item('BlueMail', __('item.BlueMail'), [0x22], null, 0xf078, 0xe8 + 0xf078, 0x1d0 + 0xf078),
			new Item('RedMail', __('item.RedMail'), [0x23], null, 0xf07c, 0xe8 + 0xf07c, 0x1d0 + 0xf07c),
			new Item\Key('Key', __('item.Key'), [0x24]),
			new Item\Compass('Compass', __('item.Compass'), [0x25]),
			new Item\Upgrade\Health('HeartContainerNoAnimation', __('item.HeartContainerNoAnimation'), [0x26], null, 0xf090, 0xe8 + 0xf090, 0x1d0 + 0xf090),
			new Item('Bomb', __('item.Bomb'), [0x27], null, 0xeff4, 0xe8 + 0xeff4, 0x1d0 + 0xeff4),
			new Item('ThreeBombs', __('item.ThreeBombs'), [0x28], null, 0xf0a0, 0xe8 + 0xf0a0, 0x1d0 + 0xf0a0),
			new Item('Mushroom', __('item.Mushroom'), [0x29], null, 0xeff8, 0xe8 + 0xeff8, 0x1d0 + 0xeff8),
			new Item('RedBoomerang', __('item.RedBoomerang'), [0x2A], null, 0xefec, 0xe8 + 0xefec, 0x1d0 + 0xefec),
			new Item\Bottle('BottleWithRedPotion', __('item.BottleWithRedPotion'), [0x2B], null, 0xf030, 0xe8 + 0xf030, 0x1d0 + 0xf030),
			new Item\Bottle('BottleWithGreenPotion', __('item.BottleWithGreenPotion'), [0x2C], null, 0xf034, 0xe8 + 0xf034, 0x1d0 + 0xf034),
			new Item\Bottle('BottleWithBluePotion', __('item.BottleWithBluePotion'), [0x2D], null, 0xf038, 0xe8 + 0xf038, 0x1d0 + 0xf038),
			new Item\BottleContents('RedPotion', __('item.RedPotion'), [0x2E]),
			new Item\BottleContents('GreenPotion', __('item.GreenPotion'), [0x2F]),
			new Item\BottleContents('BluePotion', __('item.BluePotion'), [0x30]),
			new Item('TenBombs', __('item.TenBombs'), [0x31], null, 0xf0a4, 0xe8 + 0xf0a4, 0x1d0 + 0xf0a4),
			new Item\BigKey('BigKey', __('item.BigKey'), [0x32]),
			new Item\Map('Map', __('item.Map'), [0x33]),
			new Item('OneRupee', __('item.OneRupee'), [0x34], null, 0xf0a8, 0xe8 + 0xf0a8, 0x1d0 + 0xf0a8),
			new Item('FiveRupees', __('item.FiveRupees'), [0x35], null, 0xf0ac, 0xe8 + 0xf0ac, 0x1d0 + 0xf0ac),
			new Item('TwentyRupees', __('item.TwentyRupees'), [0x36], null, 0xf0b0, 0xe8 + 0xf0b0, 0x1d0 + 0xf0b0),
			new Item\Pendant('PendantOfCourage', __('item.PendantOfCourage'), [0x37, 0x04, 0x38, 0x62, 0x00, 0x69, 0x01]),
			new Item\Pendant('PendantOfWisdom', __('item.PendantOfWisdom'), [0x38, 0x01, 0x32, 0x60, 0x00, 0x69, 0x03]),
			new Item\Pendant('PendantOfPower', __('item.PendantOfPower'), [0x39, 0x02, 0x34, 0x60, 0x00, 0x69, 0x02]),
			new Item\Bow('BowAndArrows', __('item.BowAndArrows'), [0x3A], null, 0xefe0, 0xe8 + 0xefe0, 0x1d0 + 0xefe0),
			new Item\Bow('BowAndSilverArrows', __('item.BowAndSilverArrows'), [0x3B], null, 0xefe4, 0xe8 + 0xefe4, 0x1d0 + 0xefe4),
			new Item\Bottle('BottleWithBee', __('item.BottleWithBee'), [0x3C], null, 0xf03c, 0xe8 + 0xf03c, 0x1d0 + 0xf03c),
			new Item\Bottle('BottleWithFairy', __('item.BottleWithFairy'), [0x3D], null, 0xf044, 0xe8 + 0xf044, 0x1d0 + 0xf044),
			new Item\Upgrade\Health('BossHeartContainer', __('item.BossHeartContainer'), [0x3E], null, 0xf090, 0xe8 + 0xf090, 0x1d0 + 0xf090),
			new Item\Upgrade\Health('HeartContainer', __('item.HeartContainer'), [0x3F], null, 0xf090, 0xe8 + 0xf090, 0x1d0 + 0xf090),
			new Item('OneHundredRupees', __('item.OneHundredRupees'), [0x40], null, 0xf0b8, 0xe8 + 0xf0b8, 0x1d0 + 0xf0b8),
			new Item('FiftyRupees', __('item.FiftyRupees'), [0x41], null, 0xf0b4, 0xe8 + 0xf0b4, 0x1d0 + 0xf0b4),
			new Item('Heart', __('item.Heart'), [0x42]),
			new Item\Arrow('Arrow', __('item.Arrow'), [0x43], null, 0xf094, 0xe8 + 0xf094, 0x1d0 + 0xf094),
			new Item\Arrow('TenArrows', __('item.TenArrows'), [0x44], null, 0xf09c, 0xe8 + 0xf09c, 0x1d0 + 0xf09c),
			new Item('SmallMagic', __('item.SmallMagic'), [0x45]),
			new Item('ThreeHundredRupees', __('item.ThreeHundredRupees'), [0x46], null, 0xf0bc, 0xe8 + 0xf0bc, 0x1d0 + 0xf0bc),
			new Item('TwentyRupees2', __('item.TwentyRupees2'), [0x47], null, 0xf0b0, 0xe8 + 0xf0b0, 0x1d0 + 0xf0b0),
			new Item\Bottle('BottleWithGoldBee', __('item.BottleWithGoldBee'), [0x48], null, 0xf040, 0xe8 + 0xf040, 0x1d0 + 0xf040),
			new Item('OcarinaActive', __('item.OcarinaActive'), [0x4A], null, 0xf020, 0xe8 + 0xf020, 0x1d0 + 0xf020),
			new Item('PegasusBoots', __('item.PegasusBoots'), [0x4B], null, 0xf060, 0xe8 + 0xf060, 0x1d0 + 0xf060),
			new Item\Upgrade\Bomb('BombUpgrade5', __('item.BombUpgrade5'), [0x51], null, 0xf2c8, 0xf2cc, 0xf2d0),
			new Item\Upgrade\Bomb('BombUpgrade10', __('item.BombUpgrade10'), [0x52], null, 0xf2d4, 0xf2d8, 0xf2dc),
			new Item\Upgrade\Bomb('BombUpgrade50', __('item.BombUpgrade50'), [0x4C]),
			new Item\Upgrade\Arrow('ArrowUpgrade5', __('item.ArrowUpgrade5'), [0x53]),
			new Item\Upgrade\Arrow('ArrowUpgrade10', __('item.ArrowUpgrade10'), [0x54]),
			new Item\Upgrade\Arrow('ArrowUpgrade70', __('item.ArrowUpgrade70'), [0x4D]),
			new Item\Upgrade\Magic('HalfMagic', __('item.HalfMagic'), [0x4E], null, 0xf0c0, 0xe8 + 0xf0c0, 0x1d0 + 0xf0c0),
			new Item\Upgrade\Magic('QuarterMagic', __('item.QuarterMagic'), [0x4F], null, 0xf0c4, 0xe8 + 0xf0c4, 0x1d0 + 0xf0c4),
			new Item\Programmable('Programmable1', __('item.Programmable1'), [0x55]),
			new Item\Programmable('Programmable2', __('item.Programmable2'), [0x56]),
			new Item\Programmable('Programmable3', __('item.Programmable3'), [0x57]),
			new Item('SilverArrowUpgrade', __('item.SilverArrowUpgrade'), [0x58], null, 0xefe4, 0xe8 + 0xefe4, 0x1d0 + 0xefe4),
			new Item('Rupoor', __('item.Rupoor'), [0x59]),
			new Item('RedClock', __('item.RedClock'), [0x5B]),
			new Item('BlueClock', __('item.BlueClock'), [0x5C]),
			new Item('GreenClock', __('item.GreenClock'), [0x5D]),
			new Item\Sword('ProgressiveSword', __('item.ProgressiveSword'), [0x5E], null, 0xf2bc, 0xf2c0, 0xf2c4),
			new Item\Shield('ProgressiveShield', __('item.ProgressiveShield'), [0x5F], null, 0xf2b0, 0xf2b4, 0xf2b8),
			new Item('ProgressiveArmor', __('item.ProgressiveArmor'), [0x60], null, 0xf298, 0xf29c, 0xf2a0),
			new Item('ProgressiveGlove', __('item.ProgressiveGlove'), [0x61], null, 0xf2a4, 0xf2a8, 0xf2ac),
			new Item('singleRNG', __('item.singleRNG'), [0x62]),
			new Item('multiRNG', __('item.multiRNG'), [0x63]),
			new Item\Event('Triforce', __('item.Triforce'), [0x6A]),
			new Item('PowerStar', __('item.PowerStar'), [0x6B]),
			new Item('TriforcePiece', __('item.TriforcePiece'), [0x6C]),
			new Item\Map('MapLW', __('item.MapLW'), [0x70]),
			new Item\Map('MapDW', __('item.MapDW'), [0x71]),
			new Item\Map('MapA2', __('item.MapA2'), [0x72]),
			new Item\Map('MapD7', __('item.MapD7'), [0x73]),
			new Item\Map('MapD4', __('item.MapD4'), [0x74]),
			new Item\Map('MapP3', __('item.MapP3'), [0x75]),
			new Item\Map('MapD5', __('item.MapD5'), [0x76]),
			new Item\Map('MapD3', __('item.MapD3'), [0x77]),
			new Item\Map('MapD6', __('item.MapD6'), [0x78]),
			new Item\Map('MapD1', __('item.MapD1'), [0x79]),
			new Item\Map('MapD2', __('item.MapD2'), [0x7A]),
			new Item\Map('MapA1', __('item.MapA1'), [0x7B]),
			new Item\Map('MapP2', __('item.MapP2'), [0x7C]),
			new Item\Map('MapP1', __('item.MapP1'), [0x7D]),
			new Item\Map('MapH1', __('item.MapH1'), [0x7E]),
			new Item\Map('MapH2', __('item.MapH2'), [0x7F]),
			new Item\Compass('CompassA2', __('item.CompassA2'), [0x82]),
			new Item\Compass('CompassD7', __('item.CompassD7'), [0x83]),
			new Item\Compass('CompassD4', __('item.CompassD4'), [0x84]),
			new Item\Compass('CompassP3', __('item.CompassP3'), [0x85]),
			new Item\Compass('CompassD5', __('item.CompassD5'), [0x86]),
			new Item\Compass('CompassD3', __('item.CompassD3'), [0x87]),
			new Item\Compass('CompassD6', __('item.CompassD6'), [0x88]),
			new Item\Compass('CompassD1', __('item.CompassD1'), [0x89]),
			new Item\Compass('CompassD2', __('item.CompassD2'), [0x8A]),
			new Item\Compass('CompassA1', __('item.CompassA1'), [0x8B]),
			new Item\Compass('CompassP2', __('item.CompassP2'), [0x8C]),
			new Item\Compass('CompassP1', __('item.CompassP1'), [0x8D]),
			new Item\Compass('CompassH1', __('item.CompassH1'), [0x8E]),
			new Item\Compass('CompassH2', __('item.CompassH2'), [0x8F]),
			new Item\BigKey('BigKeyA2', __('item.BigKeyA2'), [0x92]),
			new Item\BigKey('BigKeyD7', __('item.BigKeyD7'), [0x93]),
			new Item\BigKey('BigKeyD4', __('item.BigKeyD4'), [0x94]),
			new Item\BigKey('BigKeyP3', __('item.BigKeyP3'), [0x95]),
			new Item\BigKey('BigKeyD5', __('item.BigKeyD5'), [0x96]),
			new Item\BigKey('BigKeyD3', __('item.BigKeyD3'), [0x97]),
			new Item\BigKey('BigKeyD6', __('item.BigKeyD6'), [0x98]),
			new Item\BigKey('BigKeyD1', __('item.BigKeyD1'), [0x99]),
			new Item\BigKey('BigKeyD2', __('item.BigKeyD2'), [0x9A]),
			new Item\BigKey('BigKeyA1', __('item.BigKeyA1'), [0x9B]),
			new Item\BigKey('BigKeyP2', __('item.BigKeyP2'), [0x9C]),
			new Item\BigKey('BigKeyP1', __('item.BigKeyP1'), [0x9D]),
			new Item\BigKey('BigKeyH1', __('item.BigKeyH1'), [0x9E]),
			new Item\BigKey('BigKeyH2', __('item.BigKeyH2'), [0x9F]),
			new Item\Key('KeyH2', __('item.KeyH2'), [0xA0]),
			new Item\Key('KeyH1', __('item.KeyH1'), [0xA1]),
			new Item\Key('KeyP1', __('item.KeyP1'), [0xA2]),
			new Item\Key('KeyP2', __('item.KeyP2'), [0xA3]),
			new Item\Key('KeyA1', __('item.KeyA1'), [0xA4]),
			new Item\Key('KeyD2', __('item.KeyD2'), [0xA5]),
			new Item\Key('KeyD1', __('item.KeyD1'), [0xA6]),
			new Item\Key('KeyD6', __('item.KeyD6'), [0xA7]),
			new Item\Key('KeyD3', __('item.KeyD3'), [0xA8]),
			new Item\Key('KeyD5', __('item.KeyD5'), [0xA9]),
			new Item\Key('KeyP3', __('item.KeyP3'), [0xAA]),
			new Item\Key('KeyD4', __('item.KeyD4'), [0xAB]),
			new Item\Key('KeyD7', __('item.KeyD7'), [0xAC]),
			new Item\Key('KeyA2', __('item.KeyA2'), [0xAD]),
			new Item\Key('KeyGK', __('item.KeyGK'), [0xAF]),

			new Item\SuperMetroid('Grapple', 'Grappling Beam', [0xB0], null, 0xef17, 0x54 + 0xef17, 0xa8 + 0xef17),
			new Item\SuperMetroid('XRay', 'X-Ray Scope', [0xB1], null, 0xef0f, 0x54 + 0xef0f, 0xa8 + 0xef0f),
			new Item\SuperMetroid('Varia', 'Varia Suit', [0xB2], null, 0xef07, 0x54 + 0xef07, 0xa8 + 0xef07),
			new Item\SuperMetroid('SpringBall', 'Spring Ball', [0xB3], null, 0xef03, 0x54 + 0xef03, 0xa8 + 0xef03),
			new Item\SuperMetroid('Morph', 'Morphing Ball', [0xB4], null, 0xef23, 0x54 + 0xef23, 0xa8 + 0xef23),
			new Item\SuperMetroid('ScrewAttack', 'Screw Attack', [0xB5], null, 0xef1f, 0x54 + 0xef1f, 0xa8 + 0xef1f),
			new Item\SuperMetroid('Gravity', 'Gravity Suit', [0xB6], null, 0xef0b, 0x54 + 0xef0b, 0xa8 + 0xef0b),
			new Item\SuperMetroid('HiJump', 'Hi-Jump Boots', [0xB7], null, 0xeef3, 0x54 + 0xeef3, 0xa8 + 0xeef3),
			new Item\SuperMetroid('SpaceJump', 'Space Jump', [0xB8], null, 0xef1b, 0x54 + 0xef1b, 0xa8 + 0xef1b),
			new Item\SuperMetroid('Bombs', 'Bombs', [0xB9], null, 0xeee7, 0x54 + 0xeee7, 0xa8 + 0xeee7),
			new Item\SuperMetroid('SpeedBooster', 'Speed Booster', [0xBA], null, 0xeef7, 0x54 + 0xeef7, 0xa8 + 0xeef7),
			new Item\SuperMetroid('ChargeBeam', 'Charge Beam', [0xBB], null, 0xeeeb, 0x54 + 0xeeeb, 0xa8 + 0xeeeb),
			new Item\SuperMetroid('IceBeam', 'Ice Beam', [0xBC], null, 0xeeef, 0x54 + 0xeeef, 0xa8 + 0xeeef),
			new Item\SuperMetroid('WaveBeam', 'Wave Beam', [0xBD], null, 0xeefb, 0x54 + 0xeefb, 0xa8 + 0xeefb),
			new Item\SuperMetroid('Spazer', 'Spazer', [0xBE], null, 0xeeff, 0x54 + 0xeeff, 0xa8 + 0xeeff),
			new Item\SuperMetroid('Plasma', 'Plasma Beam', [0xBF], null, 0xef13, 0x54 + 0xef13, 0xa8 + 0xef13),
			new Item\SuperMetroid('ETank', 'Energy Tank', [0xC0], null, 0xeed7, 0x54 + 0xeed7, 0xa8 + 0xeed7),
			new Item\SuperMetroid('ReserveTank', 'Reserve Tank', [0xC1], null, 0xef27, 0x54 + 0xef27, 0xa8 + 0xef27),
			new Item\SuperMetroid('Missile', 'Missile', [0xC2], null, 0xeedb, 0x54 + 0xeedb, 0xa8 + 0xeedb),
			new Item\SuperMetroid('Super', 'Super Missile', [0xC3], null, 0xeedf, 0x54 + 0xeedf, 0xa8 + 0xeedf),
			new Item\SuperMetroid('PowerBomb', 'Power Bomb', [0xC4], null, 0xeee3, 0x54 + 0xeee3, 0xa8 + 0xeee3),

			new Item\Crystal('Crystal1', __('item.Crystal1'), [null, 0x02, 0x34, 0x64, 0x40, 0x7F, 0x06]),
			new Item\Crystal('Crystal2', __('item.Crystal2'), [null, 0x10, 0x34, 0x64, 0x40, 0x79, 0x06]),
			new Item\Crystal('Crystal3', __('item.Crystal3'), [null, 0x40, 0x34, 0x64, 0x40, 0x6C, 0x06]),
			new Item\Crystal('Crystal4', __('item.Crystal4'), [null, 0x20, 0x34, 0x64, 0x40, 0x6D, 0x06]),
			new Item\Crystal('Crystal5', __('item.Crystal5'), [null, 0x04, 0x32, 0x64, 0x40, 0x6E, 0x06]),
			new Item\Crystal('Crystal6', __('item.Crystal6'), [null, 0x01, 0x32, 0x64, 0x40, 0x6F, 0x06]),
			new Item\Crystal('Crystal7', __('item.Crystal7'), [null, 0x08, 0x34, 0x64, 0x40, 0x7C, 0x06]),
			new Item\Event('RescueZelda', __('item.RescueZelda'), [null]),
			new Item\Event('DefeatAgahnim', __('item.DefeatAgahnim'), [null]),
			new Item\Event('BigRedBomb', __('item.BigRedBomb'), [null]),
			new Item\Event('DefeatAgahnim2', __('item.DefeatAgahnim2'), [null]),
			new Item\Event('DefeatGanon', __('item.DefeatGanon'), [null]),

			new Item\Event('DefeatPhantoon', 'Defeat Phantoon', [null]),
			new Item\Event('DefeatKraid', 'Defeat Kraid', [null]),
			new Item\Event('DefeatDraygon', 'Defeat Draygon', [null]),
			new Item\Event('DefeatRidley', 'Defeat Ridley', [null]),
			new Item\Event('DefeatMotherBrain', 'Defeat Mother Brain', [null]),
		]);

		// Logical aliases
		static::$items->addItem(new ItemAlias('UncleSword', 'ProgressiveSword'));
		static::$items->addItem(new ItemAlias('ShopKey', 'KeyGK'));
		static::$items->addItem(new ItemAlias('ShopArrow', 'Arrow'));

		return static::all();
	}

	/**
	 * Create a new Item
	 *
	 * @param string $name Unique name of item
	 * @param string $nice_name Well formatted name for item
	 * @param array $bytes data to write to Location addresses
	 * @param array|null $address Addresses in ROM to write back Location data if set
	 *
	 * @return void
	 */
	public function __construct($name, $nice_name, $bytes, $address = null) {
		$this->name = $name;
		$this->nice_name = $nice_name;
		$this->bytes = (array) $bytes;
		$this->address = (array) $address;
	}

	/**
	 * Get the target of this item, which happens to be this item.
	 *
	 * @return $this
	 */
	public function getTarget() {
		return $this;
	}

	/**
	 * Get an ItemAlias version of this.
	 *
	 * DO NOT USE: completely untested.
	 *
	 * @return ItemAlias
	 */
	public function setTarget(Item $item) {
		return new ItemAlias($this->getName(), $item->getName());
	}

	/**
	 * Get the name of this Item
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the nice name of this Item
	 *
	 * @return string
	 */
	public function getNiceName() {
		return $this->nice_name;
	}

	/**
	 * Get the bytes to write
	 *
	 * @return array
	 */
	public function getBytes() {
		return $this->bytes;
	}

	/**
	 * Get the addresses to write to
	 *
	 * @return array
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Link this item to a Region
	 *
	 * @return $this
	 */
	public function linkRegion(Region $region) {
		$this->linked_region = $region;

		return $this;
	}

	/**
	 * Get the region to which this item has been linked.
	 *
	 * @return Region
	 */
	public function getLinkedRegion() {
		return $this->linked_region;
	}

	/**
	 * serialized version of Item
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->name . serialize($this->bytes);
	}
}
