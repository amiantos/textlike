<?php

// Who Is Character Attacking? 
function get_victim($current_character_id) {
	$query = "SELECT attacking FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		$victim = $row['attacking'];
	}
	return $victim;
}

// Get victim health 
function get_victim_health($mob_id) {
	$query = "SELECT cur_health FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		$health = $row['cur_health'];
	}
	return $health;
}

// Get victim name 
function get_victim_name($mob_id) {
	$query = "SELECT disposition,kind FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		if ($row['boss'] == 0) {
			$name = $row['disposition']." ".$row['kind'];
		} else {
			$name = "Boss ".$row['kind'];
		}
	}
	return $name;
}


// Get victim level 
function get_victim_level($mob_id) {
	$query = "SELECT level FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		
			$name = $row['level'];
		
	}
	return $name;
}

// Get victim weakness 
function get_victim_weakness($mob_id) {
	$query = "SELECT weakness FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		$name = $row['weakness'];
	}
	return $name;
}

// Get victim resistance 
function get_victim_resistance($mob_id) {
	$query = "SELECT resistance FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	// List Characters 
	while($row = mysql_fetch_array($result)) {
		$name = $row['resistance'];
	}
	return $name;
}

// Write to Battlelog 
function write_battle_history($history,$mob_id,$character_id,$type,$turns,$mob_name,$location) {
	$query = "INSERT INTO battlelog (character_id,mob_id,history,type,turn,created_for,part,location) VALUES ('$character_id','$mob_id','$history','$type','$turns','$character_id','$mob_name','$location')";
	mysql_query($query) or die(mysql_error());
}

// Get Battlelog 
function get_battlelog($attacking,$current_character_id,$num_lines,$turn) {
	// Grab first strike if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='first-strike')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$firststrike = $row['location'];
			$firststrikeenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab character hit if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='chit')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$chit = $row['location'];
			$chitenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab character tome hit if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='chit-tome')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$chittome = $row['location'];
			$chittomeenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab character bare hit if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='barehit')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$barehit = $row['location'];
			$barehitenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab enemy hit if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='ehit')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$ehit = $row['location'];
			$ehitenemy = $row['part'];
			$enemy = $row['part'];
		}
	}

	// Grab enemy miss if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='emiss')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$emiss = $row['location'];
			$emissenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab player miss if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='cmiss')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$cmiss = $row['location'];
			$cmissenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab player miss if there is one...
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='cmiss-tome')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$cmisstome = $row['location'];
			$cmisstomeenemy = $row['part'];
			$enemy = $row['part'];
		}
	}
	// Grab weapon break
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='weapon')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$weapon = $row['location'];
		}
	}
	// Grab armor break
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='armor')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$armor = $row['location'];
		}
	}
	// Grab tome break
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='tome')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$tome = $row['location'];
		}
	}
	// Grab bandages
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='bandage')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$bandage = $row['type'];
		}
	}
	// Grab defense
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='defend')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$defend = $row['type'];
		}
	}
	// Grab bleeding for mob
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='ebleed')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$bleedpercent = $row['part'];
			$ebleedenemy = $row['location'];
		}
		if ($bleedpercent > .00) { $bleed_level = 1; }
		if ($bleedpercent > .02) { $bleed_level = 2; }
		if ($bleedpercent > .04) { $bleed_level = 3; }
		if ($bleedpercent > .07) { $bleed_level = 4; }
		if ($bleedpercent > .10) { $bleed_level = 5; }
		if ($bleedpercent > .25) { $bleed_level = 6; }
		if ($bleedpercent > .35) { $bleed_level = 7; }
	}

	// Grab bleeding for character
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking') AND (turn='$turn') AND (type='cbleed')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) {
		while($row = mysql_fetch_array($result)) {
			$bleedpercent = $row['part'];
			$cbleedenemy = $row['location'];
		}
		if ($bleedpercent > .00) { $cbleed_level = 1; }
		if ($bleedpercent > .02) { $cbleed_level = 2; }
		if ($bleedpercent > .04) { $cbleed_level = 3; }
		if ($bleedpercent > .07) { $cbleed_level = 4; }
		if ($bleedpercent > .10) { $cbleed_level = 5; }
		if ($bleedpercent > .25) { $cbleed_level = 6; }
		if ($bleedpercent > .35) { $cbleed_level = 7; }
	}


	// $firststrike $chit $ehit $cmiss $chit $ebleed $cbleed $weapon $armor $bandage

	if (isset($barehit)) {
		if (isset($ehit)) {
			if ($ehit == 'torso') {
				echo "<P class='room-desc'>You slapped at the ".$ehitenemy." with your bare hands until it tired of your shenanigans, and stabbed you in the chest.</P>";
			} else if ($ehit == 'head') {
					echo "<P class='room-desc'>Your fists couldn't stop the ".$ehitenemy."'s blade from slicing into the side of your head. Blood trickles down over your ear.";
				} else {
				echo "<P class='room-desc'>You punched the ".$ehitenemy." as hard as you could, but it didn't do much to deter it from slicing into your ".$ehit.".</P>";
			}
		}
		if (isset($emiss)) {
			echo "<P class='room-desc'>Your boxing skills are paying off: you punched at the ".$emissenemy." a couple times, then deftly leapt out of the way before it could retaliate.</P>";
		}


	}

	if (isset($bandage)) {
		// Let's just handle ALL variations on bandaging now.
		if (isset($ehit)) {
			// Bandaged, then enemy hit us. Aww.
			echo "<P class='room-desc'>You bandaged your wounds. ";
			if ($ehit == 'torso') {
				echo "Before you could ready yourself the ".$ehitenemy." lunged forward and stabbed the tip of its blade into your chest.</P>";
			} else if ($ehit == 'head') {
					echo "Before you could ready yourself, the ".$ehitenemy." leapt toward you, cutting down into your neck.";
				} else {
				echo "Before you could ready yourself the ".$ehitenemy." lunged forward and sliced open your ".$ehit.". ";
			}
		}
		if (isset($emiss)) {
			// Bandaged, then enemy missed like a BITCH
			echo "<P class='room-desc'>You bandaged your wounds, and deftly leapt out of the way before the ".$emissenemy." managed to strike you.</P>";
		}
	}

	if (isset($firststrike)) {
		if (isset($chit) && isset($emiss)) {
			// Combination of first strike, player hit, and enemy miss.
			if ($firststrike == 'you') {
				echo "<P class='room-desc'>You snuck up behind the ".$firststrikeenemy." and laid it out with a mighty blow. While you catch your breath, the ".$firststrikeenemy." slowly rises.</P>";
			} else { echo "This shouldn't happen."; }
		}
		if (isset($cmiss) && isset($ehit)) {
			// Combination of first strike, player miss, and enemy hit.
			if ($firststrike == 'you') {
				if ($ehit == 'torso') {
					echo "<P class='room-desc'>You tried to sneak up behind the ".$firststrikeenemy." but strangely as you went in to attack, it vanished as if it never existed at all. The blade of the ".$firststrikeenemy." pierces your back from behind.</P>";
				} else if ($ehit == 'head') {
						echo "<P class='room-desc'>You tried to sneak up behind the ".$firststrikeenemy." but before you even knew what happened, it grabbed you by the arm and cut its blade into the side of your head.</P>";
					} else {
					echo "<P class='room-desc'>You tried to sneak up behind the ".$firststrikeenemy." but it heard you coming. Turning around faster than you expected, with a snarl it cut into your ".$ehit.".</P>";
				}
			} else { echo "This shouldn't happen."; }
		}

		if (isset($chit) && isset($ehit)) {
			// Combination of first strike, player miss, and enemy hit.
			if ($firststrike == 'you') {
				echo "<P class='room-desc'>You snuck up behind the ".$firststrikeenemy." and sliced open its ".$chit.", then the ".$ehitenemy." spun around and sliced open your ".$ehit.".</P>";
			} else { echo "This shouldn't happen."; }
		}


		if (isset($cmiss) && isset($emiss)) {
			// Combination of first strike, player miss and enemy miss.
			echo "<P class='room-desc'>You tried to catch the ".$firststrikeenemy." off guard, but it saw you coming and readied itself for your approach.</P>";
		}
		if (!isset($cmiss) && !isset($chit) && isset($emiss)) {
			echo "<P class='room-desc'>The ".$firststrikeenemy." caught you by surprise, but you were able to dodge its attack.</P>";
		}
		if (!isset($cmiss) && !isset($chit) && isset($ehit)) {
			if ($ehit == 'torso') {
				echo "<P class='room-desc'>Suddenly a blade pierces your chest, belonging to the ".$firststrikeenemy." you thought you were going to attack.</P>";
			} else if ($ehit == 'head') {
					echo "<P class='room-desc'>Suddenly a blade slices into your neck, belonging to the ".$firststrikeenemy." you thought you were going to attack.</P>";
				} else {
				echo "<P class='room-desc'>The ".$firststrikeenemy." caught you by surprise, and sliced into your ".$ehit.".</P>";
			}
		}
	}



	if (!isset($bandage) && !isset($firststrike) && !isset($chittome) && !isset($cmisstome)) {
		if (isset($chit) && isset($ehit)) {
			echo "<P class='room-desc'>";

			// EXPAND

			// Add conditional statements here...

			if ($chit == 'head' && $ehit == 'head') {

				echo "You and the $chitenemy rush at each other, and both land strikes on the other. Blood trickles down your cheek, the $chitenemy licks blood from a cut on its lips.";

			} else {

				// non-conditional...
				if ($chit == 'head') {
					echo "You poke the ".$chitenemy." in the face with your blade.";
				} else if ($chit == 'torso') {
						echo "You jam the tip of your blade into the ".$chitenemy."'s chest as hard as you can.";
					} else if ($chit == 'left leg' || $chit == 'right leg') {
						echo "You swing low and slice into the ".$chitenemy."'s ".$chit.".";
					} else if ($chit == 'left arm' || $chit == 'right arm') {
						echo "You swing your blade in from the side and slice into the ".$chitenemy."'s ".$chit.".";
					}

				echo " ";

				if ($ehit == 'head') {
					echo "Enraged, the ".$ehitenemy." swings wildly and lands a strike on your face.";
				} else if ($ehit == 'torso') {
						echo "The ".$ehitenemy." rushes you and stabs you in the chest.";
					} else if ($ehit == 'left leg' || $ehit == 'right leg') {
						echo "The ".$ehitenemy." swings low and slices into your ".$ehit.".";
					} else if ($ehit == 'left arm' || $ehit == 'right arm') {
						echo "The ".$ehitenemy." comes in from the side and cuts into your ".$ehit.".";
					}

			}

			echo "</P>";



		}
		if (isset($chit) && isset($emiss)) {
			if ($chit == 'head') {
				echo "<P class='room-desc'>You grapple with the ".$chitenemy." and slice your blade across its throat. It staggers backward, clutching at the wound dripping blood down its chest.</P>";
			} else if ($chit == 'torso') {
					echo "<P class='room-desc'>The ".$chitenemy." made a clumsy move, and you exploited the weakness to jam your blade into its chest as hard as you could.</P>";
				} else {
				echo "<P class='room-desc'>You sliced into the ".$chitenemy."'s ".$chit.", and when it tried to return blows, you leapt out of the way.</P>";

			}
		}
		if (isset($cmiss) && isset($ehit)) {
			if ($ehit == 'head') {
				echo "<P class='room-desc'>You went in to attack the ".$cmissenemy.", but it deftly leapt behind you, raking its blade across the side of your face.</P>";
			} else if ($ehit == 'torso') {
					echo "<P class='room-desc'>The ".$cmissenemy." quickly dodged your blow, and retaliated with a piercing strike to the side of your chest.</P>";
				} else {
				echo "<P class='room-desc'>You tried to attack the ".$cmissenemy.", but it dodged your blow, and sliced into your ".$ehit.".</P>";
			}
		}
		if (isset($cmiss) && isset($emiss)) {
			// NEED TO ADD MORE OF THESE !!!!!!!!!!!!!!!!!!!!!!!!!!!!
			$what = rand(1,5);
			if ($what == 1) {
				echo "<P class='room-desc'>As you go in to strike, the ".$cmissenemy." stumbles, thwarting your blow and its own.</P>";
			} else if ($what == 2) {
					echo "<P class='room-desc'>You leap over the ".$cmissenemy."'s blade, and shove it back across the room.</P>";

				} else if ($what == 3) {
					echo "<P class='room-desc'>The ".$cmissenemy." sidesteps your attack and kicks your legs out from under you.</P>";

				} else if ($what == 4) {
					echo "<P class='room-desc'>You sidestepped the ".$cmissenemy."'s attack, then stumbled and lost your balance.</P>";

				} else if ($what == 5) {
					echo "<P class='room-desc'>Your swing was too wide and your blade clattered into the wall. The ".$cmissenemy." laughs so hard that it doesn't even bother trying to attack you.</P>";

				}
		}
		if (isset($emiss) && !isset($cmiss) && !isset($chit) && !isset($barehit) && !isset($chittome) && !isset($cmisstome) && !isset($defend)) {
			echo "<P class='room-desc'>As the ".$emissenemy." moves in to strike you, it stumbles, thwarting its own attack. You take a moment to laugh at how stupid it is.</P>";
		}
		if (isset($ehit) && !isset($cmiss) && !isset($chit) && !isset($barehit) && !isset($chittome) && !isset($cmisstome) && !isset($defend)) {
			if ($ehit == 'head') {
				echo "<P class='room-desc'>The ".$ehitenemy." had no patience for you, and swung its blade into the side of your head.</P>";
			} else if ($ehit == 'torso') {
					echo "<P class='room-desc'>As you decided upon equipment, the ".$ehitenemy." screamed with rage and stabbed its blade into your chest.</P>";

				} else {
				echo "<P class='room-desc'>As you decided upon equipment, the ".$ehitenemy." got the drop on you, slicing into your ".$ehit.".</P>";

			}
		}
		if (isset($ehit) && !isset($cmiss) && !isset($chit) && !isset($barehit) && !isset($chittome) && !isset($cmisstome) && isset($defend)) {
			if ($ehit == 'head') {
				echo "<P class='room-desc'>You steeled yourself against the ".$ehitenemy."'s coming attack, but it still clobbered you in the side of your head.</P>";
			} else if ($ehit == 'torso') {
					echo "<P class='room-desc'>You braced yourself as the ".$ehitenemy." screamed with rage and stabbed its blade into your chest.</P>";

				} else {
				echo "<P class='room-desc'>Your defensive posture did no good, the ".$ehitenemy." got the drop on you, slicing into your ".$ehit.".</P>";

			}
		}
		if (isset($emiss) && !isset($cmiss) && !isset($chit) && !isset($barehit) && !isset($chittome) && !isset($cmisstome) && isset($defend)) {
			echo "<P class='room-desc'>Your defensive posture allowed you to quickly dodge the ".$emissenemy."'s attack.</P>";
		}
	}
	
	if (isset($chittome) || isset($cmisstome)) {
		echo "<P class='room-desc'>";
		if (isset($ehit)) {
			echo "<P class='room-desc'>";
			echo "The $ehitenemy lands a hit on your $ehit before you step back and raise your tome into the air...";
			echo "</P>";
		}
		if ($chittome == 'Rot') {
			echo "<P class='room-desc'>";
			echo "The pages of your tome flutter as flies pour from it and envelope the $chittomeenemy. The stench of death fills the air as the $chittomeenemy writhes on the floor, scratching at the maggots squirming in its rotting flesh.";
			echo "</P>";
		}
		if ($chittome == 'Flame') {
			echo "<P class='room-desc'>";
			echo "Fire pours out of your tome, flowing across the floor in waves toward the $chittomeenemy. Backed against the wall, the fire spreads up their legs and engulfs the $chittomeenemy's entire body. The screams are almost unbearable, perhaps you should try to put out the flames with your blade?";
			echo "</P>";
		}
		if ($chittome == 'Frost') {
			echo "<P class='room-desc'>";
			echo "A chill moves through the air as ice crystals begin to form on the $chittomeenemy. Fear fills its eyes as its skin begins to crack. Pieces of the $chittomeenemy fall to the floor and melt into little bloody flakes of flesh.";
			echo "</P>";
		}
		if ($chittome == 'Unicorn') {
			echo "<P class='room-desc'>";
			echo "A majestic unicorn, so beautiful that it stuns you for a moment, bursts forth from the pages of your tome. Rearing up, it delivers a savage kick to the $chittomeenemy. After galloping around the room, the unicorn lowers its head and skewers the $chittomeenemy through the chest with its horn. Vanishing in a poof of glitter and rainbows, you hear a distant whinny.";
			echo "</P>";
		}
		if (isset($cmisstome)) {
			echo "<P class='room-desc'>";
			echo "Your tome does nothing.";
			echo "</P>";
			echo "<P class='room-desc'>";
			echo "The $cmisstomeenemy laughs, \"Tough luck.\"";
			echo "</P>";
		}
	}

	if (isset($weapon)) {
		// Weapon broke
		echo "<P class='room-desc'>That last blow signaled the end of your weapon. You let the broken pieces fall from your hands.</P>";
	}
	if (isset($armor)) {
		// Armor broke
		echo "<P class='room-desc'>Your armor has been rendered completely useless. You shrug it from your shoulders and let it fall to the floor in pieces.</P>";
	}
	if (isset($tome)) {
		// tome broke
		echo "<P class='room-desc'>The tome in your hand has crumbled to dust.</P>";
	}



	// Throw in something random for fun...
	$what = rand(1,3000);
	if ($what == 1) {
		echo "<P class='room-desc'>You think you hear the ".$enemy." say something mean about your mother. You ask it to repeat itself, and it just snickers. \"You heard me,\" the ".$enemy." snarls.</P>";
	}
	if ($what == 2) {
		echo "<P class='room-desc'>You try to remember why you're here, why you're fighting, but you can't. Maybe you should just give up. Just let this ".$enemy." hack you to pieces, then it'd finally all be over.</P>";
	}
	if ($what == 3) {
		echo "<P class='room-desc'>This ".$enemy." whimpers pathetically.</P>";
	}
	if ($what == 4) {
		echo "<P class='room-desc'>There was a time and a place where the person you are now would horrify the person you were, but luckily you've forgotten about all that and it can't bother you.</P>";
	}
	if ($what == 5) {
		echo "<P class='room-desc'>Wait, what was that? Where'd she go?</P>";
	}
	if ($what == 6) {
		echo "<P class='room-desc'>When was the last time you slept? Do you remember?</P>";
	}
	if ($what == 7) {
		echo "<P class='room-desc'>You cough. The $enemy mimics you with an exaggerated cough. \"Pathetic human, I'll fix that cough for you soon enough,\" it sniggers.</P>";
	}
	if ($what == 8) {
		echo "<P class='room-desc'>Ph'nglui mglw'nafh Cthulhu R'lyeh wgah'nagl fhtagn.</P>";
	}
}

// Player Takes Damage 
function take_damage($damage,$character_id,$attacking) {
	$new_damage = $damage;
	// Load Character Info 
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$armor = $row['equippedArmor'];
	}
	// Load Armor Info 
	$query = "SELECT * FROM armors WHERE (id='$armor')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$protection = $row['protect'];
	}
	// Load Some Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
		$equipped_weapon = $row['equipped_weapon'];
	}
	// Load Weapon Info 
	$query = "SELECT * FROM weapons WHERE (id='$equipped_weapon')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$damage = $row['damage'];
	}
	// Get Str Bonus 
	$str_damage = $new_damage - $damage;
	// Calculate Damage Minus Armor Protection 
	$new_damage = $new_damage - $protection;
	if ($new_damage <= 0) { $new_damage = 0; }
	// Add back str bonus 
	if ($new_damage == 0) {
		$new_damage = $new_damage + $str_damage;
	}
	// Determine Which Appendage Is Struck And Apply Damage
	$rand_number = rand(1,100);
	if ($rand_number > 94 & $rand_number < 101) {
		$query = "UPDATE characters SET head=head+'$new_damage' WHERE id='$character_id'";
		$location = "head";
	}
	if ($rand_number > 79 & $rand_number < 95) {
		$query = "UPDATE characters SET torso=torso+'$new_damage' WHERE id='$character_id'";
		$location = "torso";
	}
	if ($rand_number > 0 & $rand_number < 20) {
		$query = "UPDATE characters SET upper_left=upper_left+'$new_damage' WHERE id='$character_id'";
		$location = "left arm";
	}
	if ($rand_number > 19 & $rand_number < 40) {
		$query = "UPDATE characters SET upper_right=upper_right+'$new_damage' WHERE id='$character_id'";
		$location = "right arm";
	}
	if ($rand_number > 39 & $rand_number < 60) {
		$query = "UPDATE characters SET lower_left=lower_left+'$new_damage' WHERE id='$character_id'";
		$location = "left leg";
	}
	if ($rand_number > 59 & $rand_number < 80) {
		$query = "UPDATE characters SET lower_right=lower_right+'$new_damage' WHERE id='$character_id'";
		$location = "right leg";
	}
	mysql_query($query) or die(mysql_error());
	// Record damage to Battelog 
	$turns = num_of_turns($character_id);
	if ($location == 'head') {
		$history = "The ".$mob_name." sliced into your neck with its blade.<!--(".round($new_damage)." total:".$damage.")-->";
	} else if ($location == 'torso') {
			$history = "The ".$mob_name." stabbed you in the chest.<!--(".round($new_damage)." total:".$damage.")-->";
		} else if ($location == 'left arm') {
			$history = "The ".$mob_name." cut into your left arm.<!--(".round($new_damage)." total:".$damage.")-->";
		} else if ($location == 'right arm') {
			$history = "The ".$mob_name." cut into your right arm.<!--(".round($new_damage)." total:".$damage.")-->";
		} else if ($location == 'left leg') {
			$history = "The ".$mob_name." swung low and sliced into your left leg.<!--(".round($new_damage)." total:".$damage.")-->";
		} else if ($location == 'right leg') {
			$history = "The ".$mob_name." swung low and sliced into your right leg.<!--(".round($new_damage)." total:".$damage.")-->";
		}
	write_battle_history($history,$attacking,$character_id,'ehit',$turns,$mob_name,$location);
	// Damage Armor 
	damage_armor($armor);
}

// Mob Takes Damage 
function mob_take_damage($damage,$character_id,$attacking) {
	$new_damage = $damage;
	// Load Character Info 
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$equipped_weapon_id = $row['equippedWeapon'];
	}
	$query = "SELECT * FROM mobs WHERE (id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$armor = $row['equipped_armor'];
	}
	// Load Some Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
	}
	// Load Armor Info 
	$query = "SELECT * FROM armors WHERE (id='$armor')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$protection = $row['protect'];
	}
	// Load Character Weapon Info 
	$query = "SELECT * FROM weapons WHERE (id='$equipped_weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$weap_damage = $row['damage'];
	}
	// Get strength modifier 
	$str_damage = $new_damage - $weap_damage;
	// Calculate Damage Minus Armor Protection 
	$new_damage = $new_damage - $protection;
	if ($new_damage <= 0) { $new_damage = 0; }
	if ($equipped_weapon_id == 0) { $new_damage = $damage; }
	// Add strength modifier back 
	if ($new_damage < $str_damage) {
		$new_damage = $new_damage + $str_damage;
	}
	// Determine Which Appendage Is Struck And Apply Damage
	$rand_number = rand(1,100);
	if ($rand_number > 94 & $rand_number < 101) {
		$query = "UPDATE mobs SET head=head+'$new_damage' WHERE id='$attacking'";
		$location = "head";
	}
	if ($rand_number > 79 & $rand_number < 95) {
		$query = "UPDATE mobs SET torso=torso+'$new_damage' WHERE id='$attacking'";
		$location = "torso";
	}
	if ($rand_number > 0 & $rand_number < 20) {
		$query = "UPDATE mobs SET upper_left=upper_left+'$new_damage' WHERE id='$attacking'";
		$location = "left arm";
	}
	if ($rand_number > 19 & $rand_number < 40) {
		$query = "UPDATE mobs SET upper_right=upper_right+'$new_damage' WHERE id='$attacking'";
		$location = "right arm";
	}
	if ($rand_number > 39 & $rand_number < 60) {
		$query = "UPDATE mobs SET lower_left=lower_left+'$new_damage' WHERE id='$attacking'";
		$location = "left leg";
	}
	if ($rand_number > 59 & $rand_number < 80) {
		$query = "UPDATE mobs SET lower_right=lower_right+'$new_damage' WHERE id='$attacking'";
		$location = "right leg";
	}
	mysql_query($query) or die(mysql_error());
	// Record damage to Battelog 
	$turns = num_of_turns($character_id);
	if ($equipped_weapon_id == 0) { $history = "You wailed on the ".$mob_name." with your bare hands.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
		write_battle_history($history,$attacking,$character_id,'barehit',$turns,$mob_name,$location);
	} else {
		if ($location == 'head') {
			$history = "You sliced open the ".$mob_name."\'s neck.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
		} else if ($location == 'torso') {
				$history = "You stabbed your blade into the ".$mob_name."\'s stomach.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
			} else if ($location == 'left arm') {
				$history = "The ".$mob_name."\'s eyes flashed with terror as you cut into its left arm.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
			} else if ($location == 'right arm') {
				$history = "You left a seeping wound in the ".$mob_name."\'s right arm.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
			} else if ($location == 'left leg') {
				$history = "You jammed your blade into the ".$mob_name."\'s left thigh.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
			} else if ($location == 'right leg') {
				$history = "The ".$mob_name." howled in pain as you sliced into its right leg.<!--(".round($new_damage)." total:".$damage." protection: ".$protection.")-->";
			}
		write_battle_history($history,$attacking,$character_id,'chit',$turns,$mob_name,$location);
	}

}

// Mob Takes Damage from Tome 
function mob_take_damage_tome($damage,$character_id,$attacking) {
	// Load Character Info 
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$equipped_tome_id = $row['equippedTome'];
	}
	// Load Some Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
		$weak = $row['weakness'];
		$resist = $row['resistance'];
	}
	// Load Character Tome Info 
	$query = "SELECT * FROM tomes WHERE (id='$equipped_tome_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$tome_damage = $row['damage'];
		$shortname = $row['shortName'];
	}
	if ($shortname == $weak) { $damage = $damage * 1.25; }
	if ($shortname == $resist) { $damage = $damage * 0.50; }

	// Apply Damage
	$query = "UPDATE mobs SET cur_health=cur_health-'$damage' WHERE id='$attacking'";
	mysql_query($query) or die(mysql_error());
	
	// Record damage to Battelog 
	$turns = num_of_turns($character_id);
		write_battle_history($history,$attacking,$character_id,'chit-tome',$turns,$mob_name,$shortname);

}

// Mob Attack! 
function mob_attack($mob_id,$current_character_id) {
	// Load Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
		$mob_weapon = $row['equipped_weapon'];
		$mob_dex = $row['dexterity'];
		$mob_str = $row['strength'];
	}
	// Grab defense
	$turn_check = num_of_turns($current_character_id);
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$mob_id') AND (turn='$turn_check') AND (type='defend')";
	$result = mysql_query($query) or die(mysql_error());
	$reports = mysql_num_rows($result);
	if ($reports == 1) { $defend = 1; }
	// Determine if Attack Missed 
	$char_dex = char_attribute($current_character_id,'dexterity');
	$difference = $char_dex - $mob_dex;
	$char_dice =1;
	$mob_dice = 2;
	if ($defend == 1) { $char_dice = 2;$mob_dice = 1; }
	$mob_roll = diceroll($mob_dice);
	$player_roll = diceroll($char_dice) + $difference;
	// If Player Wins, Mob misses! 
	if ($player_roll > $mob_roll) {
		$turns = num_of_turns($current_character_id);
		$pick = rand(0,2);
		if ($pick == 0) { $history = "You defly lept out of the way of the ".$mob_name."\'s thrust. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		if ($pick == 1) { $history = "The ".$mob_name." swung at you but you shrugged it off. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		if ($pick == 2) { $history = "The ".$mob_name." lunged forward, but you\'re just too fast. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		write_battle_history($history,$mob_id,$current_character_id,'emiss',$turns,$mob_name,0);
		// Otherwise he loses and gets attacked 
	} else if ($player_roll <= $mob_roll) {
			// Load Weapon Info 
			$query = "SELECT * FROM weapons WHERE (id='$mob_weapon')";
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_array($result)) {
				$damage = $row['damage'];
			}
			$damage = $damage + ($mob_str * 0.5);
			take_damage($damage,$current_character_id,$mob_id);
		}


}

// Initialize Battle 
function init_battle($attacking,$current_character_id) {
	// Load Some Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
	}
	// Check to see if Battle has already been initialized 
	$query = "SELECT * FROM battlelog WHERE (character_id='$current_character_id') AND (mob_id='$attacking')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	// Battle Not Initialized, Start It: 
	if ($numrows == 0) {
		// Who gets the drop? 
		$player_dex = char_attribute($current_character_id,'dexterity');
		$enemy_dex = mob_attribute($attacking,'dexterity');
		// If Player Wins, He Wins the First Turn! 
		if ($player_dex > ($enemy_dex - rand(0,5))) {
			$turns = num_of_turns($current_character_id);
			$history = "You caught the ".$mob_name." unaware and take the initiative $player_dex vs $enemy_dex!";
			write_battle_history($history,$attacking,$current_character_id,'first-strike',$turns,$mob_name,'you');
			player_attack($attacking,$current_character_id);
			end_turn($characterid);
			// Otherwise he loses and gets attacked 
		} else {
			// Run Monster Attack! 
			$turns = num_of_turns($current_character_id);
			$history = "The ".$mob_name." jumps in to attack as soon as you approach!";
			write_battle_history($history,$attacking,$current_character_id,'first-strike',$turns,$mob_name,'them');
			end_turn($current_character_id);
		}
	}
	// Battle Initialized, Do Nothing 
	else {
	}
}

// Player Attack 
function player_attack($mob_id,$current_character_id) {
	// Load Player Info 
	$query = "SELECT * FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$weapon = $row['equippedWeapon'];
		$char_dex = $row['dexterity'];
		$char_str = $row['strength'];
	}
	// Load Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
		$mob_dex = $row['dexterity'];
	}
	// Determine if Attack Missed 
	$difference = $char_dex - $mob_dex;
	$char_dice = 2;
	$mob_dice = 1;
	$mob_roll = diceroll($mob_dice);
	$player_roll = diceroll($char_dice) + $difference;
	// If Player Wins, He Hits! 
	if ($player_roll >= $mob_roll) {
		// Load Weapon Info 
		$query = "SELECT * FROM weapons WHERE (id='$weapon')";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)) {
			$damage = $row['damage'];
		}
		// Add Str Modifier Dmg 
		$damage = $damage + ($char_str * 0.5);
		mob_take_damage($damage,$current_character_id,$mob_id);
		damage_weapon($weapon);
		end_turn($current_character_id);
		// Otherwise he loses and gets attacked 
	} else {
		$turns = num_of_turns($current_character_id);
		$pick = rand(0,2);
		if ($pick == 0) { $history = "The ".$mob_name." managed to dodge your attack. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		if ($pick == 1) { $history = "You almost hit the ".$mob_name.", but it leapt from your reach. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		if ($pick == 2) { $history = "The ".$mob_name." is just too fast for you to land any blows on. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";}
		write_battle_history($history,$mob_id,$current_character_id,'cmiss',$turns,$mob_name,0);
		// Damage Weapon 
		end_turn($current_character_id);
	}
}

// Player Tome 
function player_tome($mob_id,$current_character_id) {
	// Load Player Info 
	$query = "SELECT * FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$tome = $row['equippedTome'];
		$char_dex = $row['dexterity'];
	}
	// Load Mob Info 
	$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
		$mob_dex = $row['dexterity'];
	}
	// Determine if Attack Missed 
	$difference = $char_dex - $mob_dex;
	$char_dice = 3;
	$mob_dice = 1;
	$mob_roll = diceroll($mob_dice);
	$player_roll = diceroll($char_dice) + $difference;
	// If Player Wins, He Hits! 
	if ($player_roll >= $mob_roll) {
		// Load Tome Info 
		$query = "SELECT * FROM tomes WHERE (id='$tome')";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)) {
			$damage = $row['damage'];
		}
		// damage mob directly
		mob_take_damage_tome($damage,$current_character_id,$mob_id);
		damage_tome($tome);
		end_turn($current_character_id);
		// Otherwise he loses and gets attacked 
	} else {
		$turns = num_of_turns($current_character_id);
		$history = "The ".$mob_name." dodged your spell. <!-- Your roll: ".$player_roll." Enemy roll: ".$mob_roll."-->";
		write_battle_history($history,$mob_id,$current_character_id,'cmiss-tome',$turns,$mob_name,0);
		// Damage Weapon 
		end_turn($current_character_id);
	}
}

// Player Defend 
function player_defend($mob_id,$current_character_id) {
	// Load Mob Info 
	$query = "SELECT kind FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$mob_name = $row['kind'];
	}
	$turns = num_of_turns($current_character_id);
	$history = "You steeled yourself against the ".$mob_name."\'s coming attack.";
	write_battle_history($history,$mob_id,$current_character_id,'defend',$turns,$mob_name,0);
	end_turn($current_character_id);
}


?>