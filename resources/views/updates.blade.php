@extends('layouts.default', ['title' => 'Updates - '])

@section('content')
<h3>2018-11-25 - Version 10.1</h3>
<div class="card card-body bg-light mb-3">
	<ul>
  	<li>Checkerboard cave now correctly has a requirement for gloves when accessed through the Lower Norfair to Misery Mire portal.</li>
  	<li>Suitless mama turtle now always requires high-jump. (Hard mode)</li>
  	<li>Right side upper norfair hellruns is now in logic with only speedbooster. (Hard mode)</li>
  	<li>Golden Torizo has its logic fixed to require charge beam or super missiles to fight him.</li>
  	<li>The logic no longer requires a short charge to escape Golden Torizo. (Normal mode)</li>
  	<li>Ice beam is now an additional requirement to access suitless springball. (Hard mode)</li>
  	<li>Some additional fixes has been done to the item pool allocations that could cause some item placements not to be possible.</li>
  	<li>A seed identifier has been added to the file select screen before starting a new game.</li>
  	<li>The bomb blocks in the "climb room" during the escape will now automatically be cleared preventing a potential softlock.</li>
  	<br />
  	<li><h4>Huge thanks to all contributors for helping out with fixes and patches for V10.1</h4></li>
	</ul>
<h2>2018-07-27 v10</h2>
<div class="card card-body bg-light mb-3">
	<ul>
		<li>Dynamic text is now added back in, which means that checking pedestal and tables will now give a hint to what items is at those locations.</li>
		<li>Progressive items in the same room in SM now works properly, although the second item will show incorrect graphics.</li>
		<li>A bunch of general logic bugfixes that should fix a few weird possible edge cases.</li>
		<li>Super Metroid logic has been renamed and is now "Normal" and "Hard", where "Normal" is the old "Casual" and "Hard" is the old "Tournament"</li>
		<li>Normal logic has been reworked and should now be a bit more consistent in terms of required techniques.</li>
		<li>Hard logic has had a major rework and now includes a few new required tricks, details below:
			<ul>
				<li>The cross-game portal in maridia is now better implemented in logic, so there's now a chance for suitless maridia through the portal using only Morph and Hi-Jump or Spring Ball.</li>
				<li>This also means that access to Wrecked Ship without Power Bombs is possible through the maridia portal and the "Forgotten Highway".</li>
				<li>The green brinstar missiles furthest in behind the Reserve tank is now in logic with only Morph and Screw Attack.</li>
				<li>Spring ball jumps have been added into logic in many new places, for example Red Tower, X-Ray room and more.</li>
				<li>Hi-Jump missile back is now in logic with only Morph (Make sure to not enter the Hi-Jump room in this case or you will softlock)</li>
				<li>Getting to the green door leading to Norfair reserve now assumes only the damage boost on a waver to get to it.</li>
				<li>Lower Norfair item restrictions are now slightly reduced due to better use of the cross-game portal.</li>
				<li>Snail clipping to the Missile and Super packs in Aqueduct is now in logic.</li>
				<li>Spring Ball is now in logic suitless, assuming Hi-Jump, Space Jump, Spring Ball and Grapple.</li>
				<li>All sand pit items are now in logic suitless with Hi-Jump, with the left sandpit items also requiring Space Jump or Spring Ball and the right sandpit PB's requiring Spring Ball.</li>
			</ul>
		</li>
		<li>Back of desert in ALTTP can now be in logic without gloves using the Lower Norfair cross-game portal and Mirror.</li>
		<li>Checkerboard cave now correctly checks for access through the cross-game portal as well.</li>
		<br/>
		<li>A recommended guide to the new V10 logic has been created by WildAnaconda69 and can be found here: <a href="https://www.twitch.tv/videos/286489494">https://www.twitch.tv/videos/286489494</a>.</li>
		<br/>
		<li><h4>Huge thanks to all contributors for helping out with fixes and patches for V10.</h4></li>
	</ul>
</div>

<h2>v30</h2>
<div class="card card-body bg-light mb-3">
	<ul>
		<li>Front end rewrite into Vue framework... you have no idea how much went into this</li>
		<li>Site translations! De rien! Bitte! ¡De nada!</li>
<!--		<li>Fixed a bug where swords weren’t tracked correctly in the stats in Retro</li>
		<li>Fixed a bug where the freestanding item in Hera did not increment the Compass item tracker (Easy/Keysanity)</li>
		<li>Fixed the keysanity game crashes that happened frequently  in Palace of Darkness in Keysanity</li> -->
		<li>Fixed the bug in Zora’s Domain where the ledge item would have graphical issues</li>
<!--		<li>Fixed the bug where the 2nd copy of Silver Arrows in Easy didn’t revert back to 10 arrows</li>
		<li>Fixed Uncle accidentally giving 300 rupees along with the Bow in non-retro </li>
		<li>Fixed a bug where maps/compasses didn’t track correctly in Easy/Keysanity if bosses have a progressive sword</li>
		<li>Uncle no longer gives ammo refills unless you’re playing Standard/Randomized or Standard/Swordless</li>
		<li>Uncle now has an equal chance to give Swords in Standard/Randomized</li> -->
		<li>Disabled stored weak/strong EG when exiting Palace of Darkness (No Glitches logic only)</li>
		<li>Zora will tell you if if you don’t have enough rupees</li>
<!--		<li>Easy difficulty starts with 6 hearts and has 3 extra containers in the pool which revert to rupees once you have 20</li>
		<li>Added logic in Retro to account for the progressive sword in the take-any cave, and keys and arrows</li>
		<li>Added Enemizer</li>
		<li>Hard+ mode no longer has Fairies or Full magic’s available in the prize packs</li> -->
		<li>The Lake Hylia Great fairy is back from vacation and now sells her upgrades like any shop</li>
		<li>Relatedly, the capacity upgrades have been removed from the item pool</li>
<!--		<li>Added Inverted mode!</li> -->
		<li>HINTS! go check your telepathic tiles for sometimes helpful hints</li>
		<li>There is the only one save file</li>
		<li>There is a tracker on file select and end screens now</li>
		<li>You now get full refills on purchased upgrades</li>
<!--		<li>Customizer got prize pack editing</li> -->
		<li>Great fairy bottle refills are completely automated and only have 1 text box now, so faster fills</li>
<!--		<li>Added new player options<br />
			<img src="https://s3.us-east-2.amazonaws.com/alttpr/sprites.30.lg.png"
				alt="Link sprite options" style="width:50%" /></li> -->
	</ul>
</div>

<h2>VT8.29</h2>
<div class="card card-body bg-light mb-3">
	<ul>
<!--		<li>Easy mode now gets 2 chances at silver arrow upgrade</li>
		<li>Triforce hunt lessens the chance of finding triforce pieces in GT</li>
		<li>Removed the GT Junk pre-fill for all glitched modes</li> -->
		<li>Warning message on generation page when you select anything other than No Glitches logic</li>
<!--		<li>Small keys in spoiler for key-sanity</li>
		<li>Maps/Compasses logically required for completion of dungeon in keysanity</li> -->
		<li>Mirror warp sound is back in background music disable</li>
		<li>Better placement of maps/compassess in dungeons</li>
<!--		<li>Byrna no longer protects you in hard/expert/insanity, but also uses normal amounts of magic</li>
		<li>Customizer:<ul>
			<li>Added "Test Generation" button, so you don't bloat the DB when just testing ideas</li>
			<li>Removed some unuseful items</li>
			<li>Fixes for better crystal/pendant placement (less broken generations)</li>
			<li>Fairy bottle fix</li>
			<li>Item listing cleanup and normalizing</li>
			<li>Bottles can be set in starting equipment</li>
			<li>Name listed in meta section</li>
			<li>Item list header should be sticky</li>
			<li>Remembers where you were</li>
			<li>Save/Restore settings!</li>
			<li>Names matter (well, more than they used to)</li>
			<li>Add notes to your custom games</li>
			<li>Set the hard mode adjustments (e.g. bottle refill)</li>
			<li>You can allow dark room navigation</li>
			<li>Pendants/Crystals can not be set for more than one dungeon</li>
			<li>Pendants/Crystals should be more helpful when selecting them</li>
			<li>You can set Link's starting health</li>
		</ul></li>
		<li>Key-sanity logic fixes</li> -->
		<li>Sahasrahla and Bomb Shop dude will mark your map after you talk to them</li>
		<li>Stored water walking glitch is back</li>
<!--		<li>Triforce Hunt is now always 20/30 for all difficulties</li>
		<li>All Lamps in Easy are before dark rooms</li>
		<li>Extra Lamps in Easy are really rupees now</li>
		<li>Flute time in credits fixed</li> -->
		<li>Better boss logic for future fun</li>
<!--		<li>Added quick swap functionality</li> -->
		<li>If you use a headered rom, the site will try to strip that header out before use (thanks Myramong)</li>
<!--		<li>Added new player options<br />
			<img src="https://s3.us-east-2.amazonaws.com/alttpr/sprites.29.lg.png"
				alt="Link sprite options" style="width:50%" /></li> -->
	</ul>
</div>

<h2>Older</h2>
<div class="card card-body bg-light mb-3">
	<ul>
		<li><a href="http://alttpr.com/updates">Visit ALttPR.com for more</a></li>
	</ul>
</div>
@overwrite
