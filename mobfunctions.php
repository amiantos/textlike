<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

/* Generate a Mob */
function generate_mob($character_id,$boss) {
	$room_level = room_level(character_room($character_id));
	$level = $room_level;
	$benign = rand(1,8);
	if ($benign == 1) { $level = $level + rand(-1,1); }
	$critical = rand(1,40);
	if ($critical == 1) { $level = $level + 3; }
	if ($critical == 2) { $level = $level + 5; }
	if ($level < 1) { $level = 1;}
	
	if ($boss>0) { $level = $room_level+10; }
	/* Write New Enemy To Table */
	$query = "INSERT INTO mobs (level,characterid,created_for,boss) VALUES ('$level','$character_id','$character_id','$boss')";
	mysql_query($query) or die(mysql_error());
	$mob_id = mysql_insert_id();
	/* Get and return that ID */

	/* Calculate Available Points */
	$points = ($level - 1) * 1;
	$total_points = $points;
	$half_total = $points/2.5;
	$str = 0;
	$sta = 0;
	$dex = 0;
	/* Set Enemy Stats */
	while ($points > 0) {
		/* Pick A Stat To Increase */
		$stat = rand(0,2);
		/* Pick Random Number Of Points To Assign */
		$increase = 1;
		/* Make sure increase is not more than available points, if so, set it to available points */
		if ($increase > $points) { $increase = $points; }
		/* Write Modification To Database */
		if ($stat == 0) {
			if ($str < $half_total) {
				$query = "UPDATE mobs SET strength=strength+$increase WHERE id='$mob_id'";$str++;
				mysql_query($query) or die(mysql_error());
				/* Decrease points based on addition */
				$points = $points - $increase;
			}
		} else if ($stat == 1) {
			if ($sta < $half_total) {
					$query = "UPDATE mobs SET stamina=stamina+$increase WHERE id='$mob_id'";$sta++;
					mysql_query($query) or die(mysql_error());
					/* Decrease points based on addition */
					$points = $points - $increase;
				}
		} else if ($stat == 2) {
			if ($dex < $half_total) {
					$query = "UPDATE mobs SET dexterity=dexterity+$increase WHERE id='$mob_id'";$dex++;
					mysql_query($query) or die(mysql_error());
					/* Decrease points based on addition */
					$points = $points - $increase;
				}
		}
	}

	/* Find Mob Kind */
	$mob_disposition = rand(0,28);
	if ($mob_disposition == 0) { $disposition = "demented "; }
	if ($mob_disposition == 1) { $disposition = "psychotic "; }
	if ($mob_disposition == 2) { $disposition = "heretical "; }
	if ($mob_disposition == 3) { $disposition = "deranged "; }
	if ($mob_disposition == 4) { $disposition = "drooling "; }
	if ($mob_disposition == 5) { $disposition = "mumbling "; }
	if ($mob_disposition == 6) { $disposition = "tiny "; }
	if ($mob_disposition == 7) { $disposition = "barmy "; }
	if ($mob_disposition == 8) { $disposition = "batty "; }
	if ($mob_disposition == 9) { $disposition = "berserk "; }
	if ($mob_disposition == 10) { $disposition = "bonkers "; }
	if ($mob_disposition == 11) { $disposition = "crazed "; }
	if ($mob_disposition == 12) { $disposition = "erratic "; }
	if ($mob_disposition == 13) { $disposition = "idiotic "; }
	if ($mob_disposition == 14) { $disposition = "insane "; }
	if ($mob_disposition == 15) { $disposition = "kooky "; }
	if ($mob_disposition == 16) { $disposition = "nutty "; }
	if ($mob_disposition == 17) { $disposition = "schizo "; }
	if ($mob_disposition == 18) { $disposition = "unbalanced "; }
	if ($mob_disposition == 19) { $disposition = "unglued "; }
	if ($mob_disposition == 20) { $disposition = "unhinged "; }
	if ($mob_disposition == 21) { $disposition = "naked "; }
	if ($mob_disposition == 22) { $disposition = "wacky "; }
	if ($mob_disposition == 23) { $disposition = "balanced "; }
	if ($mob_disposition == 24) { $disposition = "reasonable "; }
	if ($mob_disposition == 25) { $disposition = "responsible "; }
	if ($mob_disposition == 26) { $disposition = "sensible "; }
	if ($mob_disposition == 27) { $disposition = "innocent "; }
	if ($mob_disposition == 28) { $disposition = "totally wacked out "; }

	$mob_kind = rand(0,19);
	if ($mob_kind == 0) { $kind = "molestor"; }
	if ($mob_kind == 1) { $kind = "slenderman"; }
	if ($mob_kind == 2) { $kind = "demon"; }
	if ($mob_kind == 3) { $kind = "priest"; }
	if ($mob_kind == 4) { $kind = "heretic"; }
	if ($mob_kind == 5) { $kind = "cultist"; }
	if ($mob_kind == 6) { $kind = "stranger"; }
	if ($mob_kind == 7) { $kind = "cyclops"; }
	if ($mob_kind == 8) { $kind = "doppleganger"; }
	if ($mob_kind == 9) { $kind = "golem"; }
	if ($mob_kind == 10) { $kind = "imp"; }
	if ($mob_kind == 11) { $kind = "mothman"; }
	if ($mob_kind == 12) { $kind = "ogre"; }
	if ($mob_kind == 13) { $kind = "satyr"; }
	if ($mob_kind == 14) { $kind = "siren"; }
	if ($mob_kind == 15) { $kind = "vampire"; }
	if ($mob_kind == 16) { $kind = "succubus"; }
	if ($mob_kind == 17) { $kind = "werewolf"; }
	if ($mob_kind == 18) { $kind = "hobbit"; }
	if ($mob_kind == 19) { $kind = "bystander"; }


	$query = "UPDATE mobs SET kind='$kind',disposition='$disposition' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());

	/* Assign Mob A Weapon */
	$mob_weapon = generate_weapon($character_id,$level);
	$query = "UPDATE mobs SET equipped_weapon='$mob_weapon' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());
	$query = "UPDATE weapons SET mobid='$mob_id' WHERE id='$mob_weapon'";
	mysql_query($query) or die(mysql_error());

	/* Assign Mob An Armor */
	$mob_armor = generate_armor($character_id,$level);
	$query = "UPDATE mobs SET equipped_armor='$mob_armor' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());
	$query = "UPDATE armors SET mobid='$mob_id' WHERE id='$mob_armor'";
	mysql_query($query) or die(mysql_error());

	/* Generate Mob Health Points */
	/* Load Mob Info */
	$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$stamina_level = $row['stamina'];
	}
	/* Calculate Health */
	$health = 50;
	$total_health = $health + ($stamina_level-10) * 11;
	/* Write Health To Mob */
	$query = "UPDATE mobs SET total_health='$total_health',cur_health='$total_health' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());



	/* Generate Experience Points */

	/* Get Player's Experience Needed For That Level */
	$number_hell = $level;
	$exp_last = 0;
	$exp_need = 100;
	$exp_needed = 100;
	while ($number_hell > 1) {
		$exp_needed = ($exp_need - $exp_last) * 1.1;
		$exp_last = $exp_need;
		$exp_need = $exp_needed + $exp_last;
		$number_hell = $number_hell - 1;
	}

	/* Get "ideal killed enemies per level" */
	$number_fun = $level;
	$needed = 8;
	while ($number_fun > 1) {
		$needed = $needed * 1.02;
		$number_fun = $number_fun - 1;
	}
	/* Get experience */

	$exp = ($exp_needed / $needed);


	/* Write Experience */
	$query = "UPDATE mobs SET experience='$exp' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());
	
	/* Choose and Write Weakness */
	$weak_num = rand(1,3);
	if ($weak_num == 1) { $weak = 'Flame'; }
	if ($weak_num == 2) { $weak = 'Frost'; }
	if ($weak_num == 3) { $weak = 'Rot'; }
	$query = "UPDATE mobs SET weakness='$weak' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());
	
	/* Choose and Write Resistance */
	$resist_num = rand(1,3);
	while ($resist_num == $weak_num) { $resist_num = rand(1,3); }
	if ($resist_num == 1) { $resist = 'Flame'; }
	if ($resist_num == 2) { $resist = 'Frost'; }
	if ($resist_num == 3) { $resist = 'Rot'; }
	$query = "UPDATE mobs SET resistance='$resist' WHERE id='$mob_id'";
	mysql_query($query) or die(mysql_error());

	/* Function Returns New Mob ID */
	$id = $mob_id;
	return $mob_id;
}

/* Drop Mob In Room */
function place_mob_in_room($item_id,$room_id) {
	$query = "UPDATE mobs SET inRoom='$room_id' WHERE id='$item_id'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Mob Generator Check */
function mobs_genned($room_id) {
	$query = "SELECT * FROM rooms WHERE (id='$room_id') AND (mobsGenned='1')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	return $numrows;
}

/* Count Mobs In Room */
function count_mobs($current_room) {
	$query = "SELECT * FROM mobs WHERE (inRoom='$current_room')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	return $numrows;
}

/* Get Attribute Points */
function mob_attribute($mob_id,$attribute) {
	$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	$points = 0;
	while($row = mysql_fetch_array($result)) {
		if ($attribute == '1') { $points = $row['strength']; }
		if ($attribute == '2') { $points = $row['stamina']; }
		if ($attribute == '3') { $points = $row['dexterity']; }
	}
	return $points;
	$points = 0;
}

/* Load Mobs Into Room */
function load_mobs($current_room,$current_character_id,$user) {
	$char_level = current_char_level($current_character_id);
	/* Load living mobs */
	$query = "SELECT * FROM mobs WHERE (inRoom='$current_room') AND (corpse='0')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo "You see ";
		} else if ($numrows == 2) {
			echo "";
		} else if ($numrows >= 3) {
			echo "You see that there are several enemies here&mdash;";
		}
	if ($numrows > 0) { $living = 1; }
	$current_number = 1;
	while($row = mysql_fetch_array($result)) {
		$disposition = $row['disposition'];
		$name = $row['kind'];
		$level = $row['level'];
		$damage = $row['damage'];
		$mob_id = $row['id'];
		$boss = $row['boss'];
		if ($boss > 0) { $level_name = "absolutely terrifying ";} else if ($level > ($char_level+5)) {$level_name = "hulking ";} else if ($level > ($char_level+2)) {$level_name = "angry ";} else if ($level < ($char_level-1)) {$level_name = "timid ";} else {$level_name = ""; }
		// build mob name...
		if ($boss == 0) { $mob_name = $level_name.$disposition.$name; } else { $mob_name = $level_name.$name; }
		// is mob name 
		if ((substr($mob_name, 0, 1) == 'a') || (substr($mob_name, 0, 1) == 'e') || (substr($mob_name, 0, 1) == 'i') || (substr($mob_name, 0, 1) == 'o') || (substr($mob_name, 0, 1) == 'u') || (substr($mob_name, 0, 2) == 'ho')) { $middle_bit = 'an '; } else { $middle_bit = 'a '; }
		// pick the initial 'a'
		if ($current_number == 1 && $numrows == 2) { echo ucwords($middle_bit)." "; } else if ($current_number == 1) {echo $middle_bit; }
		
		if ($current_number == $numrows && $numrows > 1) { echo " and $middle_bit";}
		echo "<a href='processing.php?startattack=".$mob_id."&character=".$current_character_id."&user=".$user."'>".$mob_name."</a><!--(".$level.")-->";
		
		if ($current_number == $numrows-1) { echo ", ";} else if ($current_number < $numrows) {echo ", $middle_bit";}
		$current_number = $current_number +1;
	}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo " lurking amongst the shadows.";
		} else if ($numrows == 2) {
			echo " lurk amongst the shadows.";
		} else if ($numrows >= 3) {
			echo "&mdash;lurking amongst the shadows.";
		}
	/* Load Corpses */
	$query = "SELECT * FROM mobs WHERE (inRoom='$current_room') AND (corpse='1')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($living == 1) { echo "&nbsp;";}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo "The bloodied corpse of ";
		} else if ($numrows == 2) {
			echo "The rotting corpses of ";
		} else if ($numrows >= 3) {
			echo "A pile of corpses&mdash;consisting of the bodies of ";
		}
	$current_number = 1;
	while($row = mysql_fetch_array($result)) {
		$disposition = $row['disposition'];
		$name = $row['kind'];
		$level = $row['level'];
		$damage = $row['damage'];
		$mob_id = $row['id'];
		$boss = $row['boss'];
		if ($boss == 0) {
			$mob_name = "".$disposition.$name."";
		} else {
			$mob_name = "boss ".$name."";
		}
		
		// is mob name 
		if ((substr($mob_name, 0, 1) == 'a') || (substr($mob_name, 0, 1) == 'e') || (substr($mob_name, 0, 1) == 'i') || (substr($mob_name, 0, 1) == 'o') || (substr($mob_name, 0, 1) == 'u') || (substr($mob_name, 0, 2) == 'ho')) { $middle_bit = 'an '; } else { $middle_bit = 'a '; }
		// pick the initial 'a'
		if ($current_number == 1) {echo $middle_bit; }
		if ($current_number == $numrows && $numrows > 1) { echo " and $middle_bit";}
		
		echo $mob_name;
		
		if ($current_number == $numrows-1) { echo ", ";} else if ($current_number < $numrows) {echo ", $middle_bit";}
		$current_number = $current_number +1;
	}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo " lies on the floor.";
		} else if ($numrows == 2) {
			echo " are beginning to collect flies.";
		} else if ($numrows >= 3) {
			echo "&mdash;are really stinking up the room.";
		}

}

?>