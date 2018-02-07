<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

/* Dice Function */
function diceroll($num_dice) {
	$dice = $num_dice;
	while ($dice > 0) {
		$var = $var + rand(1,6);
		$dice = $dice - 1;
	}
	return $var;
}

/* Refresh Character Total Health */
function refresh_health_total($characterid) {
	/* Load Character Info */
	$query = "SELECT stamina,strength FROM characters WHERE (id='$characterid')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$stamina_level = $row['stamina'];
		$strength_level = $row['strength'];
	}
	/* Calculate Health */
	$health = 150;
	$total_health = $health + (($stamina_level - 10) * 30);
	/* Write Health To Character */
	$query = "UPDATE characters SET totalHealth='$total_health',cur_health='$total_health' WHERE id='$characterid'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Refresh Character Total Health Without Heal */
function refresh_health_total_special($characterid) {
	/* Load Character Info */
	$query = "SELECT stamina,strength FROM characters WHERE (id='$characterid')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$stamina_level = $row['stamina'];
		$strength_level = $row['strength'];
	}
	/* Calculate Health */
	$health = 100;
	$total_health = $health + (($stamina_level - 10) * 15);
	/* Write Health To Character */
	$query = "UPDATE characters SET totalHealth='$total_health' WHERE id='$characterid'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Refresh Character Total Carry */
function refresh_carry_total($characterid) {
	/* Load Character Info */
	$query = "SELECT strength FROM characters WHERE (id='$characterid')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$strength_level = $row['strength'];
	}
	/* Calculate Carry */
	$offset = $strength_level - 10;
	if ($offset < 0) {$offset = 0;}
	$carry = 40 + ($offset * 10);
	/* Write Carry To Character */
	$query = "UPDATE characters SET totalCarry='$carry' WHERE id='$characterid'";
	$result = mysql_query($query) or die(mysql_error());
}

/* List Characters or Show Create Form */
function list_or_create_characters($user) {
	$query = "SELECT id,name,level FROM characters WHERE user='$user'";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		echo "<h2>Your Characters</h2>";
		echo "<ol>";
		while($row = mysql_fetch_array($result)) {
			echo "<li>";
			echo "<a href='processing.php?character-select=".$row['id']."&user=".$user."' title='Click to select this character'/>";
			echo $row['name'];
			echo "</a>";
			echo " - Level: ";
			echo $row['level'];
		}
		echo "</ol>";
	}
	/* If No Characters */
	if ($numrows < 5) {
		echo "<h2>Create a Character</h2>";
		echo "<center><form name='character' action='processing.php' method='get' style='margin-left:10px;'>";
		echo "<input type='text' class='field' name='character-name' placeholder='Character Name' maxlength='18'/>";
		echo "<input type='hidden' name='user' value='". $user."'>";
		echo "<input type='submit' value='Create' />";
		echo "</form></center>";
	}
}

/* Get Selected Character As ID */
function selected_character_id($user) {
	$query = "SELECT characterSelected FROM tz_members WHERE (usr='$user')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$character = $row['characterSelected'];
		}
	}
	return $character;
}

/* Get Selected Character As Text */
function selected_character($selected_character_id) {
	$query = "SELECT name FROM characters WHERE (id='$selected_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$character = $row['name'];
		}
	}
	return $character;
}

/* Get Available Points */
function available_points($current_character_id) {
	$query = "SELECT availablePoints FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$points = $row['availablePoints'];
		}
	}
	return $points;
}

/* Get Current Level */
function current_char_level($current_character_id) {
	$query = "SELECT level FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	while($row = mysql_fetch_array($result)) {
		$points = $row['level'];
	}
	return $points;
}

/* Get Attribute Points */
function char_attribute($current_character_id,$attribute) {
	$query = "SELECT strength,stamina,dexterity,intelligence,luck FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	while($row = mysql_fetch_array($result)) {
		if ($attribute = 'strength') { $points = $row['strength']; }
		if ($attribute = 'stamina') { $points = $row['stamina']; }
		if ($attribute = 'dexterity') { $points = $row['dexterity']; }
		if ($attribute = 'intelligence') { $points = $row['intelligence']; }
		if ($attribute = 'luck') { $points = $row['luck']; }
	}
	return $points;
}

/* Get Number Of Turns */
function num_of_turns($current_character_id) {
	$query = "SELECT turns FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$points = $row['turns'];
		}
	}
	return $points;
}

/* Get Number Of Turns */
function last_level($current_character_id) {
	$query = "SELECT last_level FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$points = $row['last_level'];
		}
	}
	return $points;
}

/* Get Attributes Screen */
function character_attributes($current_character_id,$current_character,$user) {
	$available_points = available_points($current_character_id);
	$query = "SELECT strength,stamina,dexterity FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Attributes */
	echo "<table cellspacing=0 border=0 width='100%'>";
	while($row = mysql_fetch_array($result)) {
		echo "<tr><td align=center style='padding:2px;border-right:1px solid #808080;'><small style='font-variant:small-caps;'>Strength:</small> ";
		if ($available_points > 0) { echo "<a href='processing.php?add-point=strength&user=".$user."&id=".$current_character_id."' style='color:red;'/>"; }
		echo $row['strength'];
		if ($available_points > 0) { echo "</a>"; }
		echo "</td><td align=center style='padding:2px;border-right:1px solid #808080;'><small style='font-variant:small-caps;'>Stamina:</small> ";
		if ($available_points > 0) { echo "<a href='processing.php?add-point=stamina&user=".$user."&id=".$current_character_id."' style='color:red;'/>"; }
		echo $row['stamina'];
		if ($available_points > 0) { echo "</a>"; }
		echo "</td><td align=center style='padding:2px;'><small style='font-variant:small-caps;'>Dexterity:</small> ";
		if ($available_points > 0) { echo "<a href='processing.php?add-point=dexterity&user=".$user."&id=".$current_character_id."' style='color:red;'/>"; }
		echo $row['dexterity'];
		if ($available_points > 0) { echo "</a>"; }
		echo "</tr>";
	}
	echo "</table>";
}

/* Get Character Room */
function character_room($current_character_id) {
	$query = "SELECT inRoom FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	/* List Characters */
	if ($numrows != 0) {
		while($row = mysql_fetch_array($result)) {
			$room = $row['inRoom'];
		}
	}
	return $room;
}

/* Get Total Health */
function total_health($current_character_id) {
	$query = "SELECT totalHealth FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$points = $row['totalHealth'];
	}
	return $points;
}

/* Get Current Health */
function current_health($current_character_id) {
	$query = "SELECT cur_health FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$points = $row['cur_health'];
	}
	return $points;
}

/* Get Experience */
function experience($current_character_id) {
	$query = "SELECT experience FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$points = $row['experience'];
	}
	return $points;
}

/* Get Next Level Experience  */
function next_level_exp($current_character_id) {
	$query = "SELECT next_level FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$points = $row['next_level'];
	}
	return $points;
}

/* Level Gained Function */
function level_up_check($current_character_id) {
	/* Load Char Info */
	$query = "SELECT next_level,last_level,experience,level FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$next_level = $row['next_level'];
		$last_level = $row['last_level'];
		$current_exp = $row['experience'];
		$level = $row['level'];
	}
	if ($current_exp >= $next_level) {
		/* Increment User Level! */
		$next = $next_level + (($next_level - $last_level) * 1.5);
		/* Give user attribute point. */
		if ($level < 25) {
			$query = "UPDATE characters SET level=level+1,availablePoints=availablePoints+1,next_level='$next',last_level='$next_level' WHERE id='$current_character_id'";
		} else {
			$query = "UPDATE characters SET next_level='$next',last_level='$next_level' WHERE id='$current_character_id'";
		}
		mysql_query($query) or die(mysql_error());
		/* Heal User */
		refresh_health_total($current_character_id);
	}
}

/* Submit High Score and Delete */
function submit_and_delete($current_character_id) {
	/* Load Character Info */
	$query = "SELECT level,experience,name,turns FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$level = $row['level'];
		$experience = $row['experience'];
		$char_name = mysql_real_escape_string($row['name']);
		$turns = $row['turns'];
	}
	$current_room_level = room_level(character_room($current_character_id));
	if ($current_room_level <= 25){
		$floor = 25 - ($current_room_level - 1);
	} else if ($current_room_level >= 26) {
			$floor = $current_room_level - 25;
			$floor = "B".$floor;
		}
	$user_name = $_SESSION['usr'];
	$user_id = $_SESSION['id'];
	$total_score = $experience;
	if ($turns != 0) { $total_score = ($experience / ($turns/2))*100; } else {$total_score = 0;}
	$total_score = round($total_score);
	/* Write High Score To HighScores */
	$query = "INSERT INTO highscores (user_id,user_name,character_id,character_name,total_score,level,floor) VALUES ('$user_id','$user_name','$current_character_id','$char_name','$total_score','$level','$floor')";
	mysql_query($query) or die(mysql_error());
	/* Post Tweet */
	$tweet_text = "".$char_name." (".$user_name.") just died at level ".$level." with a score of ".$total_score."! http://amiantos.net/textlike";
	post_tweet($tweet_text);
	/* Delete Character and Everything Pertaining To It */
	$query = "DELETE FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM armors WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM battlelog WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM items WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM mobs WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM roomhistory WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM rooms WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM structures WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM weapons WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM tomes WHERE (created_for='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	/* De-select Character */
	$query = "UPDATE tz_members SET characterSelected='' WHERE usr='$user_name'";
	mysql_query($query) or die(mysql_error());
}

/* Get Total Carry */
function get_total_weight($current_character_id) {
	$query = "SELECT totalCarry FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$points = $row['totalCarry'];
	}
	return $points;
}

/* Get Current Weight */
function get_current_weight($current_character_id) {
	/* Load all Weapons and Armor character has */
	$query = "SELECT weight FROM weapons WHERE (characterid='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$weapon_weight = $weapon_weight + $row['weight'];
	}
	$query = "SELECT weight FROM armors WHERE (characterid='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$armor_weight = $armor_weight + $row['weight'];
	}
	$query = "SELECT weight FROM tomes WHERE (characterid='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$tome_weight = $tome_weight + $row['weight'];
	}
	$current_weight = $armor_weight + $weapon_weight + $tome_weight;
	return $current_weight;
}

/* Return player encumbrance */
function encumbered($character_id) {
	/* Load Player Info */
	$query = "SELECT encumbered FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$dead = $row['encumbered'];
	}
	return $dead;
}

/* Check Luck */
function luck_check($selected_character_id) {
	$query = "SELECT luck,level FROM characters WHERE (id='$selected_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$luck = $row['luck'];
		$level = $row['level'];
	}
	/* Calculate Points At Level */
	$points = ($level/5);
	/* Calculate average points for luck at that level */
	/* Add those to the base points */
	$avg_luck = $points + 10;
	/* Round */
	$avg_luck = round($avg_luck);
	if ($level < 5) {$avg_luck = 9;}
	/* If luck is over avg_luck then success */
	if ($luck > $avg_luck) {
		/* Luck check passes too much, let's make it half as likely */
		if (rand(0,1) == 1) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}


?>