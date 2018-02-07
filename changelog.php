<?php include('header.php'); ?>
<?php include('headerhtml.php');include 'menu.php'; ?>

<?php if($_SESSION['id']) { ?>

<div class="other-side-box">
<h1>Todo</h1>
<ul>
<li> Implement some sort of puzzle system??
<li> Rewrite "mob attack hit/character attack hit" dialogs.
</ul>

<h1>Changelog</h1>
<h3>0.8.1</h3>
<ul>
<li> Added enemy elemental resistances
<li> Whoops, fixed bug that made flame resist/weak not work.
<li> Upped auto-heal.
<li> Weakened boss mobs
<li> Lowered overall bleed amount
<li> Slowed chracter level rate</li>
<li>lowered mob health</li>
</ul>
<h3>0.8</h3>
<ul>
<li> Removed apples
<li> reinstated heal on level up
<li> made boss enemies even tougher
<li> re-did character info GUI bar and other general GUI clean up
<li> high scores reset and database wiped of all old characters
<li> Fixed divide by zero bleeding bug
<li> lowered health a little
<li> lowered item durabilities
<li> messed with bandage rarities
<li> Added "defend" option back in for battles. gives you a higher chance at blocking an attack.
<li> Improved room generator further to be more random (it had a preference for north/south before now)
</ul>
<h3>0.7.1</h3>
<ul>
<li>HUGE CHANGE: proper use of 'an' when listing enemies! Hopefully.
</ul>
<h3>0.7</h3>
<ul>
<li> Adjusted enemy EXP amounts
<li> Raised initial tome damage level
<li> Adjusted item rarities
<li> Changed item quality names
<li> Changed weapon materials
<li> BOSS MOB generated randomly on every floor, you may have to back track to find him.
<li> Boss mobs have higher chance of dropping their high level items and will drop more healing items
<li> You have to defeat the BOSS to unlock the staircase for that level.
<li> Adjusted levelling speed
<li> Adjusted initial health amount and raise to stamina
<li> Re-tooled dexterity so it is more necessary to success.
<li> Changed carry addition amount.
<li> Hopefully fixed bug that was causing 2 room floors to generate occasionally. 
<li> Lowered rooms per floor to 5 from 6
</ul>
<h3>0.6</h3>
<ul>
<li> Added Apple iOS device icons so you can "Add to Home Screen" if you want. "Web App Capable" means the game can be played without the browser interface.
<li> Changed how level cap is presented to the player: level never goes about 25, next level is always 0%.
<li> Added TOMES. Tome are magical volumes that can be used to cast powerful spells. They're relatively rare, limited in use, and are nicely powerful.
<li> Enemies are weak to a specific type of tome which'll cause bonus damage against them.
<li> Raised initial enemy health level a bit
<li>Added enemy info bar to battles, now you can see how much health an enemy has and how bad it's bleeding!
<li>Adjusted chest drops
<li>Some UI enhancements to deal with long character and mob names.
</ul>
<H3>0.5.3</H3>
<ul>
<li> Fixed bug that was causing players to always lose dexterity checks on first attacks.
</ul>
<H3>0.5.2</H3>
<ul>
<li>Added level cap back in.
<li>Messed with healing item drop rates A LOT
<li>Lowered initial health and raised attribute point bump because I realized Stamina was sort of useless otherwise
<li>Bumped up room generator room per floor amount</li>
<li>Lowered amount of enemies that spawn at lower levels
<li>Changed score calculator to have higher scores by a factor of 10.
</ul>
<H3>0.5.1</H3>
<ul>
<li> Added stone wall motif and CSS gradients/transparencies/dropshadows to make UI a little cooler.
<li> Adjusted auto-heal
<li> Changed how bleeding works so now you won't be overcome by huges amounts of bleeding really suddenly. I hope.
<li> Fixed twitter posting, now deaths will be posted to @<a href="http://twitter.com/textlike">Textlike</a> again
<Li> Fixed misc UI bugs and polishing
<li> Added "front page" to Textlike with description before you sign up or log in
<li> Changed floor room maximum to 4 rooms.
<li> Re-did inventory/equipment UI/panel
<lI> Added social media links and copyright back in
</ul>
<H3>0.5</H3>
<ul>
<li>Major UI overhaul to make the game "single screen" again.
<li>Removed level cap
<li>Adjusted auto-heal so that it becomes less pronounced as you level
<li>Adjust item rarities
<li>"fixing" mob generator so that "super specialized" mobs don't happen
<li>Added occasional special awesome chests
<li>Found out I programmed in the ability to adjust levelling by how many mobs needed to be killed... so I bumped that up a little.
<li>Messed with apple generation quite a bit so it's, well, less random
<li>Changed score calculation show score is generally higher with more variation
<li>Auto-heal rate dependant on current health not total
<li>Changed color of Punch button
<li>Made STR damage a "minimum damage" per attack to avoid situations in which somebody's armor makes them invincible
<LI>Made styles between desktop and mobile more similar.
</ul>
<H3>0.4.1</H3>
<ul>
<lI>Experimenting with various balance changes.
<li>Mobs are much more likely to drop healing items, but chests are less likely to drop them overall.
<li>Adjusted armor generator so that armors aren't totally outpaced by weapons late in the game
<li>Messed with mob bleeding again
<li>Messed with character health level and character bleeding
<li>Trying to fix this new bleeding bug...
</ul>
<h3>0.4</h3>
<ul>
<li>Broken armors and weapons automatically drop when they break in battle.
<li>Lowering enemy health level to make battles not take as long...
<li>Lowered player health level to compensate...
<li>Lowered floor height to 25.
<li>Lowered level cap to 25.
<li>After level 25 each level gain heals your health
<li>Added floor to high score record
<li>Fixed bug where enemies would spawn already dead... whoops
<li>Raise mob bleeding amount to hasten pace of game even more.
<li>Removed mob level indicators, replaced with dynamic titles (sort of like Borderlands)
<li>Overhauled battle description system. 90% complete now.
<li>Adjusted room branching generation to make the game a bit more labrynthian
</ul>
<h3>0.3.3</h3>
<ul>
<li>Added Apples back in. Apples heal you completely, but they cannot be used in battle. They should also be very, very rare.
<li>Mobs may drop a healing item when they die.
<li>Raised health level to 200.
<li>Fixed a longstanding bug with initiative to begin battles. now you can actually get stabbed before you can attack
<li>Fleshing out mob generator names for fun
<li>Changed room generator to be more simple, so things are less repetitive for now and easier on me.
<li>Simplified battle view
<li>De-couple Bandage and Apple pickups. Apples are limited to 2. Bandages, 6.
</ul>
<h3>0.3.2</h3>
<ul>
<li>Lowered initial health level to 100.
</li>
</ul>
<h3>0.3.1</h3>
<ul>
<li>Removed heal on stamina point bump
</ul>
<h3>0.3</h3>
<ul>
<li>Big version jump! Why not!
<li>Bleeding system COMPLETELY RE-DONE
<li>Adjusted item level spawns
</ul>
<h3>0.2.1</h3>
<ul>
<li>Adjusted bleeding amounts for players
<li>Adjusted bleeding for enemies
<li>Changed hit location probabilities again
<li>Lowered weapon weight very slightly
<li>Upped dead enemy drop weap/armor rate
<li>Bandages don't heal your wounds entirely, but they stop the bleeding
</ul>
<h3>0.2</h3>
<ul>
<li>Fixed negative armor generation
<li>This in turn fixed low level weapon generation as well.
<li>Increasing a skill point costs a turn
<li>Causing '0' damage to a mob is now basically impossible
<li>"Online" count now appears on desktop version again
<li>Unarmed attacks do strength modifier damage
<li>Bandage limit is now "hard"
<li>Strength adds more to damage amounts
<li>Armor generator protection toned down a bit
<li>Adjusted weapon weight
<li>Mob health adjusted
<li>Mob bleeding adjusted
<li>Adjusted mob/item level distribution
<li>Fixed one way doors
<li>Corpses now stay in rooms
<li>Minor GUI improvements
<li>Can no longer pick up broken items
<li>Increased player health gain for stamina
<li>Adjusted hit location probabilities
</ul>
<h3>0.1.9</h3>
<ul>
<li>Added sentence structure to mob/item room output
	<li>Item names adjusted
</ul>
<h3>0.1.8.1</h3>
<ul>
<li>Adjusted connecting room generator to limit amount of three/four exit rooms
<li>Adjusted stairs generator to be less predictable
	<li>Screen stylesheet so font is reasonable size on desktop
</ul>
<h3>0.1.8</h3>
<ul>
<li>Items and Monsters in room have simple sentence structure
<li>Other "UI" enhancements
</ul>
<h3>0.1.7.1</h3>
<ul>
<li>Limit to 6 bandages carryable
<li>Mob health adjusted
<li>Luck and Int removed from game
<li>Level limit set to 50
<li>Only 50 floors before basement levels
<li>Tweet from @<a href="http://twitter.com/textlike">Textlike</a> now says character name
</ul>
<h3>0.1.7</h3>
<ul>
<li>Rudimentary iPhone/Mobile interface
<li>Enemies no longer drop healing items
</ul>
<h3>0.1.6</h3>
<ul>
<li>Removed Apples
<li>Chests are more rare
<li>Mobs bleed less easily
<li>Bandages and Apples have the same chance as dropping
<li>Luck check is harder to pass -- need to work on this
<li>Passing luck check no longer offers chance of bonus healing item drop
<li>Mobs less likely to drop healing items
<li>Armor protection and bleeding have been adjusted
<li>Upped durability of weapons and amor
<li>Fixed a variety of bugs
<li>Fixed deletion mechanism
<li>Luck check is more random
</ul>
<h3>0.1.5.1</h3>
<ul>
<li>You can sign up using your own password and an email address is not required.
<li>Introductory chest drops 9 objects now, for fun.
<li>Apples now heal 50% of your health.
</ul>
<h3>0.1.5</h3>
<ul>
<li>Number of rooms per floor has been reduced to 5. Just to see.
<li>Up thru level 4 you automatically pass the Luck check. After that, your luck needs to be higher than avg for your level. E.g. at level 5, when the luck function 'turns on', it needs to be at 12. At level 25, 16. At 100, 30. High luck is essentially putting the game into 'easy mode' as drops are much more common. If you start to run out of healing items, might be worth it to try bumping up your luck to see if things change.
</ul>
<h3>0.1.4</h3>
<ul>
<li>Wounds automatically heal a percentage of your total health every turn. This means minor injuries will heal before you bleed to death... hopefully...
<li>Vials have been replaced with apples, which can only be eaten out of battle. They restore 50 HP per apple.
<li>Health item drop chance has been adjusted accordingly.
<li>Health and bleedng has been overhauled, again...
<li>RTFM/Manual above has been expanded and rewritten to fit the current version
<li>Strength governs carry now and not Stamina
</ul>
<h3>0.1.3</h3>
<ul>
<li>Battle and bleeding messages have been changed.
<li>Player bleed damage is now calculated after enemy attack per turn.
<li>Number of rooms on each floor has been decreased to 8, from 9, tho it was meant to be 10 and never was. Whoops.
</ul>
<h3>0.1.2</h3>
<ul>
<li>Monsters now are equipped with armor and weapons specific to their level.
<li>Updated Battle Log with notification types.
<li>Enemies are no longer guaranteed to drop their weapon and armor upon death.
<li>The last turn of moves is presented at the top of the Battle Log.
<li>Luck is now functioning
<li>Changed score to simply being total EXP at death, for now
<li>Changed bleeding functionality (no longer armor-based)
</ul>
<h3>0.1.1</h3>
<ul>
<li>Item weight and durability added...
<li>Durability is now functional. In theory weapons should break and become useless.
<li>You can now become over-encumbered by carrying too much.
<li>Wiped everything again because of weight/durability.
<li>Upped mob healing item drop chance.
<li>Adjusted bleeding for players down slightly.
<li>Updated How To Play
</ul>
<h3>0.1.0</h3>
<ul>
<li>Leveling and monster exp adjustment should be "final".
<li>As such, all high scores have been wiped.
<li>A tweet is sent from @<a href="https://twitter.com/textlike">textlike</a> every time a score is submitted.
<li>As requested by Shoop, bleeding is now a number by your health
</ul>
<h3>0.0.9</h3>
<ul>
<li>You can now drop unneeded weapons and armor.
<li>The text in the battlelog fades out as the fight progresses.
<li>The hunt for the attack button finally ends...
<li>Leveling had been adjusted.
</ul>
<h3>0.0.8</h3>
<ul>
<li>Game is basically playable in current state.
</ul>
</div>

<? } else { ?>

<?php } ?>

<?php include('footer.php'); ?>