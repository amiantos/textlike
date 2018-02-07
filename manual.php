<?php include('header.php'); ?><?php include('headerhtml.php');
include 'menu.php';
?><?php if($_SESSION['id']) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
    <div class="other-side-box">
        <h2>How To Play</h2>

        <P class="room-desc">In Textlike your goal is to get the highest score by progressing further in the tower and killing more enemies than anyone else.</p>
        
        <h2>Quick Start</h2>
        
        <P class="room-desc">Use the cardinal points at the bottom of each screen to move around. Open chests to find loot. Pick up loot and equip it to use it.</P>
        <P class="room-desc">Battles in Textlike revolve around <em>bleeding</em>, not direct damage (except for your tomes). If you see a number in <span style='color:red;'>RED</span> next to your health, that's how much damage you're going to take next turn (every action: 1 turn.). Use Bandages to stop the bleeding. You heal every time you level up, that percentage in your stat bar is how close you are to your next level.</P>        
        <p>Textlike is <em>sort of hard</em>. If you find yourself dying really early on, think about conserving your bandages and tomes in preparation for any coming boss-fights. You've gotta be a little strategic or else you'll just end up bleeding to death.</p>

        <h2>Movement</h2>

        <P class="room-desc">You move around by selecting from the cardinal points representing doorways out of each room. These will appear at the bottom of the screen for each room, along with any staircases that are also in the room for upward or downward movement.</p>

        <h2>Weapons, Armor, and Tomes</h2>

        <P class="room-desc"><strong>Weapons</strong> are what you use to kill enemies. Next to each weapon name you'll see numbers that look like this: (<span style='color:red;' title='Damage: 10'>10</span>/<span style='color:blue;' title='Total Durability: 14'>14</span>/<span style='color:orange;' title='Weight: 1'>1</span>) If you mouse over them, they tell you what they are, but if you're on a mobile device: the first number is total damage caused. The second number is current durability. The third number is the weight of the weapon.</p>

        <P class="room-desc"><strong>Armors</strong> work much the same way with similarly formatted stats: (<span style='color:green;' title='Protection: 9'>9</span>/<span style='color:blue;' title='Total Durability: 32'>32</span>/<span style='color:orange;' title='Weight: 11'>11</span>) The first number is total block amount. The second is durability. The third is weight.</p>

        <P class="room-desc"><strong>Tomes</strong> are similar still: (<span style='color:purple;' title='Power: 9'>45</span>/<span style='color:blue;' title='Total Durability: 2'>2</span>/<span style='color:orange;' title='Weight: 1'>1</span>) The first number is total spell damage. The second is durability. The third is weight.</p>

        <P class="room-desc">Weapons, Armors and Tomes needed to be <strong>Equipped</strong> before they can be used. If either reach 0 durability, they are broken and cannot be equipped. If your weapon or armor breaks during battle, you'll find yourself without a weapon or without defenses until you equip others.</p>

        <h2>Weight</h2>

        <P class="room-desc">You have a maximum carry weight determined by your Strength. If you carry more than this weight, you will become over-encumbered and will have to lighten your load before you can progress. Bandages and apples do not have individual weight.</p>

        <h2>Enemies</h2>

        <P class="room-desc">Enemies will generally be close to your level, but sometimes they'll be much stronger. "Angry" or "Hulking" enemies are especially powerful. "Timid" enemies are weaker than you are. Enemies have weapons generated equivalent to their level, so be cautious of hulking baddies. (But rejoice: if you kill one, you might get their high-level items!)</p>
		
		<P class="room-desc">At some point in the exploration of every floor, a very powerful boss monster will spawn randomly in one of the rooms. You have to find this monster and kill it to progress to the next floor.</p>

        <h2>Combat</h2>

        <P class="room-desc">Combat is a pretty simple affair of whacking the monster you're fighting over and over again until you or it dies. Since you're locked in the room, there's no use in fleeing. It's a battle to the death no matter what. Hit the "Attack" button to attack. If the button says "Punch", you can still attack, but you'd be better off equipping a weapon first.</p>

        <P class="room-desc">If you have a Tome equipped, you can use it by clicking "Tome" during battle.</p>
        
        <P class="room-desc">If you don't want to attack, you can "Defend". When you defend, you have a higher chance of blocking oncoming attacks.</P>

        <P class="room-desc"><strong>Note</strong>: You can equip different weapons, armors, & tomes, and use bandages during battle.</p>

        <h2>How Damage Works</h2>

        <P class="room-desc">Damage in Textlike revolves around <strong>Bleeding</strong>. You and enemies have six hit zones on your body that can be wounded. If they become wounded enough they begin to bleed out (arms bleed least severely, the head the most).</p>

        <P class="room-desc">The number in <span style="color:red;">red</span> next to your health is the amount of health you will bleed out next turn if you do not bandage your wounds. Minor wounds will sometimes heal themselves without needing bandages.</p>

        <P class="room-desc">Tomes cause <em>direct</em> damage to the enemy's health.</p>

        <h2>Healing Items</h2>

        <P class="room-desc"><strong>Bandages</strong> stop bleeding. Use one if you think you might bleed to death. You can only carry 6 bandages at a time.</p>

        <P class="room-desc">The only way to heal your actual health in Textlike is by leveling up.</p>

        <h2>Levelling and Attributes</h2>

        <P class="room-desc">Every time you kill an enemy you gain experience points. Once you gain enough for the level you're currently on, you go up a level and receive an <strong>Attribute Point</strong> you can spend on your primary attributes.</p>

        <P class="room-desc"><strong>Strength</strong> applies a damage bonus to every attack, determines your Punch strength, and controls your carry amount.</p>

        <P class="room-desc"><strong>Stamina</strong> controls your total health amount, and how much you bleed.</p>

        <P class="room-desc"><strong>Dexterity</strong> governs your first attack chance and controls how frequently you land or dodge blows.</p>

        <h2>Questions? Suggestions?</h2>

        <P class="room-desc">Feel free to <a href="mailto:amiantos@icloud.com">email me</a> if you have any questions, thoughts, suggestions, or concerns. If you have a great idea for Textlike, I'd love to hear it!</p>

        <P class="room-desc">Thanks for playing.</p>
    </div><?php } else { ?><?php } ?><?php include('footer.php'); ?>
