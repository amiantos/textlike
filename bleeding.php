<?php
/* Update Bleeding .. Durrrr */
function update_bleeding($character_id) {
	$attacking = get_victim($character_id);
	/* Load Player Info */
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$head = $row['head'];
		$torso = $row['torso'];
		$upper_left = $row['upper_left'];
		$upper_right = $row['upper_right'];
		$lower_left = $row['lower_left'];
		$lower_right = $row['lower_right'];
		$armor = $row['equippedArmor'];
		$health = $row['totalHealth'];
		$current_health = $row['cur_health'];
	}
	/* Calculate Damage */

	/* Get Max Bleed */
	$max_bleed = round($health * 0.1);
	$offset = round($health * 0.05);

	if ($head <= $offset) { $head = 0; } else { $head = $head - $offset; }
	if ($torso <= $offset) { $torso = 0; } else { $torso = $torso - $offset; }
	if ($upper_right <= $offset) { $upper_right = 0; } else { $upper_right = $upper_right - $offset; }
	if ($upper_left <= $offset) { $upper_left = 0; } else { $upper_left = $upper_left - $offset; }
	if ($lower_right <= $offset) { $lower_right = 0; } else { $lower_right = $lower_right - $offset; }
	if ($lower_left <= $offset) { $lower_left = 0; } else { $lower_left = $lower_left - $offset; }

	if ($health > 0) {
		/* Get Head Damage */
		$head_damage = round(($head / $health) * ($max_bleed * 0.4));
		/* Get Torso Damage */
		$torso_damage = round(($torso / $health) * ($max_bleed * 0.3));
		/* Get Right Arm */
		$upper_right_damage = round(($upper_right / $health) * ($max_bleed * 0.2));
		/* Get Left Arm */
		$upper_left_damage = round(($upper_left / $health) * ($max_bleed * 0.2));
		/* Get Right Leg */
		$lower_right_damage = round(($lower_right / $health) * ($max_bleed * 0.1));
		/* Get Left Left */
		$lower_left_damage = round(($lower_left / $health) * ($max_bleed * 0.1));
	}

	$damage = $head_damage + $torso_damage + $upper_left_damage + $upper_right_damage + $lower_left_damage + $lower_right_damage;
	if ($damage > $max_bleed) { $damage = $max_bleed; }

	/* Write To Character */
	if ($damage > 0) {
		$query = "UPDATE characters SET cur_health=cur_health-$damage WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	/* If In Battle, Log Bleeding To Battlelog */
	if ($attacking > 0 && $damage > 0) {
		/* Determine severity Of Bleed */
		$bleed_percent = $damage / $current_health;
		if ($current_health == 0) { $current_health = 1;}
		if ($bleed_percent > .00) { $bleed_amount = "You\'re bleeding a tiny amount"; }
		if ($bleed_percent > .02) { $bleed_amount = "You\'re bleeding a small amount"; }
		if ($bleed_percent > .04) { $bleed_amount = "You\'re bleeding a good amount"; }
		if ($bleed_percent > .065) { $bleed_amount = "You\'re bleeding quite a bit"; }
		if ($bleed_percent > .10) { $bleed_amount = "You\'re bleeding quite a lot"; }
		if ($bleed_percent > .25) { $bleed_amount = "Your wounds are grievous"; }
		if ($bleed_percent > .35) { $bleed_amount = "You will certainly bleed to death soon"; }

		$turns = num_of_turns($character_id);
		$history = "".$bleed_amount.".";
		write_battle_history($history,$attacking,$character_id,'cbleed',$turns,$bleed_percent,'you');
	}
}

/* Update Bleeding for Mobs */
function update_bleeding_mob($character_id) {
	$attacking = get_victim($character_id);
	/* Load Mobs With Damage Info */
	$query = "SELECT * FROM mobs WHERE (characterid='$character_id') AND ((head>0) OR (torso>0) OR (upper_left>0) OR (upper_right>0) OR (lower_left>0) OR (lower_right>0)) AND (corpse<1)";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$id = $row['id'];
		$name = $row['kind'];
		$head = $row['head'];
		$torso = $row['torso'];
		$upper_left = $row['upper_left'];
		$upper_right = $row['upper_right'];
		$lower_left = $row['lower_left'];
		$lower_right = $row['lower_right'];
		$armor = $row['equipped_armor'];
		$health = $row['total_health'];
		$current_health = $row['cur_health'];
		/* Get Max Bleed */
		$max_bleed = round($health * 0.6);

		/* Get Head Damage */
		$head_damage = round(($head / $health) * ($max_bleed * 0.5));
		/* Get Torso Damage */
		$torso_damage = round(($torso / $health) * ($max_bleed * 0.4));
		/* Get Right Arm */
		$upper_right_damage = round(($upper_right / $health) * ($max_bleed * 0.3));
		/* Get Left Arm */
		$upper_left_damage = round(($upper_left / $health) * ($max_bleed * 0.3));
		/* Get Right Leg */
		$lower_right_damage = round(($lower_right / $health) * ($max_bleed * 0.2));
		/* Get Left Left */
		$lower_left_damage = round(($lower_left / $health) * ($max_bleed * 0.2));

		$damage = $head_damage + $torso_damage + $upper_left_damage + $upper_right_damage + $lower_left_damage + $lower_right_damage;

		/* Write To Mob */
		$query = "UPDATE mobs SET cur_health=cur_health-$damage WHERE id='$id'";
		mysql_query($query) or die(mysql_error());
		/* If Mob Is The One You're Attacking ... */
		if ($attacking == $id && $damage > 0) {
			$turns = num_of_turns($character_id);
			if ($current_health == 0) { $current_health = 1;}
			$bleed_percent = $damage / $current_health;
			if ($bleed_percent > .00) { $bleed_amount = "The ".$name." is bleeding a tiny amount"; }
			if ($bleed_percent > .02) { $bleed_amount = "The ".$name." is bleeding a small amount"; }
			if ($bleed_percent > .04) { $bleed_amount = "The ".$name." is bleeding a good amount"; }
			if ($bleed_percent > .065) { $bleed_amount = "The ".$name." is bleeding quite a bit"; }
			if ($bleed_percent > .10) { $bleed_amount = "The ".$name." is bleeding quite a lot"; }
			if ($bleed_percent > .25) { $bleed_amount = "The ".$name."\'s wounds are grievous"; }
			if ($bleed_percent > .35) { $bleed_amount = "The ".$name." will certainly bleed to death soon"; }
			$history = "".$bleed_amount.".";
			write_battle_history($history,$attacking,$character_id,'ebleed',$turns,$bleed_percent,$name);
		}
	}
}

/* Check for Player Death */
function is_player_dead($character_id) {
	$attacking = get_victim($character_id);
	/* Load Player Info */
	$query = "SELECT cur_health FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$health = $row['cur_health'];
	}
	if ($health < 1) {
		/* Player is dead */
		/* If in battle, log death to battle log. */
		if ($attacking > 0) {
			$history = "You\'ve died from your wounds.";
			write_battle_history($history,$attacking,$character_id,'alert',0,0,0);
		}
		/* Set "player dead" flag */
		$query = "UPDATE characters SET dead=1 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		/* Player is not dead  */
	}
}

/* Return player death */
function death($character_id) {
	/* Load Player Info */
	$query = "SELECT dead FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$dead = $row['dead'];
	}
	return $dead;
}

/* Check for enemy Death */
function is_mob_dead($character_id) {
	$attacking = get_victim($character_id);
	/* Find Dead Mobs */
	$query = "SELECT * FROM `mobs` WHERE `characterid`='$character_id' AND `cur_health`<1 AND `corpse`<1";
	$result = mysql_query($query) or die(mysql_error());
	while($row = @mysql_fetch_array($result,MYSQL_ASSOC)) {
		$experience = $row['experience'];
		$name = $row['kind'];
		$id  = $row['id'];
		$room = $row['inRoom'];
		$weapon = $row['equipped_weapon'];
		$armor = $row['equipped_armor'];
		$boss = $row['boss'];
		/* Update Battlelog */
		if ($attacking == $id) {
			$turns = num_of_turns($character_id);
			$history = "You killed the ".$name.", and gained ".$experience." experience.";
			write_battle_history($history,$attacking,$character_id,'alert',$turns,'killed',$mob_name);
		}
		/* Update Room History */
		$history = "You killed the ".$name.", and gained ".$experience." experience.";
		write_room_history($history,$room,$character_id);
		/* Drop Mob Items In Room */
		$weapon_rand = rand(0,2);
		$armor_rand = rand(0,2);
		if ($boss > 0) {
			$weapon_rand = rand(0,3);
			$armor_rand = rand(0,3);
		}
		if ($weapon_rand == 1) {
			$query = "UPDATE weapons SET inRoom='$room' WHERE id='$weapon'";
			$result = mysql_query($query) or die(mysql_error());
		}
		if ($armor_rand == 1) {
			$query = "UPDATE armors SET inRoom='$room' WHERE id='$armor'";
			$result = mysql_query($query) or die(mysql_error());
		}
		// Have mob drop a healing item randomly
		$bandages_total = count_bandages($character_id);
		if ($boss == 0) {
			if (rand(0,2) == 1) { place_item_in_room(generate_item($character_id),$room);}
			if (rand(0,4) == 1) { place_item_in_room(generate_item($character_id),$room);}
		} else if ($boss > 0) {
			place_item_in_room(generate_item($character_id),$room);
			if (rand(0,5) == 1) { place_item_in_room(generate_item($character_id),$room);}
			if (rand(0,1) == 1) { place_tome_in_room(generate_tome($character_id,0),$room);}
			if (rand(0,3) == 1) { place_tome_in_room(generate_tome($character_id,0),$room);}
		}
		if (rand(0,100) == 1) { place_item_in_room(generate_item($character_id),$room);}
		if (rand(0,700) == 1) { place_item_in_room(generate_item($character_id),$room);}
		if (rand(0,2) == 1) { place_tome_in_room(generate_tome($character_id,0),$room);}
		if (rand(0,6) == 1) { place_tome_in_room(generate_tome($character_id,0),$room);}

		/* If attacking, unset attacking flag */
		if ($attacking == $id) {
			$query = "UPDATE characters SET attacking='0' WHERE id='$character_id'";
			mysql_query($query) or die(mysql_error());
		}
		/* Add experience to character */
		$query = "UPDATE characters SET experience=experience+$experience WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
		/* Delete Mob */
		$query = "UPDATE mobs SET corpse='1' WHERE (id='$id')";
		$result = mysql_query($query) or die(mysql_error());
	}
	return 1;
}
/* Return 1 if Character Bleeding */
function is_bleeding($character_id) {
	if ($character_id > 0) {
		/* Load Player Info */
		$query = "SELECT * FROM characters WHERE (id='$character_id')";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)) {
			$head = $row['head'];
			$torso = $row['torso'];
			$upper_left = $row['upper_left'];
			$upper_right = $row['upper_right'];
			$lower_left = $row['lower_left'];
			$lower_right = $row['lower_right'];
			$armor = $row['equippedArmor'];
			$health = $row['totalHealth'];
		}
		$max_bleed = round($health * 0.1);
		$offset = round($health * 0.05);

		if ($head <= $offset) { $head = 0; } else { $head = $head - $offset; }
		if ($torso <= $offset) { $torso = 0; } else { $torso = $torso - $offset; }
		if ($upper_right <= $offset) { $upper_right = 0; } else { $upper_right = $upper_right - $offset; }
		if ($upper_left <= $offset) { $upper_left = 0; } else { $upper_left = $upper_left - $offset; }
		if ($lower_right <= $offset) { $lower_right = 0; } else { $lower_right = $lower_right - $offset; }
		if ($lower_left <= $offset) { $lower_left = 0; } else { $lower_left = $lower_left - $offset; }

		/* Get Head Damage */
		$head_damage = round(($head / $health) * ($max_bleed * 0.4));
		/* Get Torso Damage */
		$torso_damage = round(($torso / $health) * ($max_bleed * 0.3));
		/* Get Right Arm */
		$upper_right_damage = round(($upper_right / $health) * ($max_bleed * 0.2));
		/* Get Left Arm */
		$upper_left_damage = round(($upper_left / $health) * ($max_bleed * 0.2));
		/* Get Right Leg */
		$lower_right_damage = round(($lower_right / $health) * ($max_bleed * 0.1));
		/* Get Left Left */
		$lower_left_damage = round(($lower_left / $health) * ($max_bleed * 0.1));



		$damage = $head_damage + $torso_damage + $upper_left_damage + $upper_right_damage + $lower_left_damage + $lower_right_damage;
		if ($damage > $max_bleed) { $damage = $max_bleed; }

		return $damage;
	} else { return 0; }
}

/* Return 1 if mob Bleeding */
function is_victim_bleeding($mob_id) {
	if ($mob_id > 0) {
		/* Load Player Info */
		$query = "SELECT * FROM mobs WHERE (id='$mob_id')";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_array($result)) {
			$id = $row['id'];
		$name = $row['kind'];
		$head = $row['head'];
		$torso = $row['torso'];
		$upper_left = $row['upper_left'];
		$upper_right = $row['upper_right'];
		$lower_left = $row['lower_left'];
		$lower_right = $row['lower_right'];
		$armor = $row['equipped_armor'];
		$health = $row['total_health'];
		$current_health = $row['cur_health'];
		}
		/* Get Max Bleed */
		$max_bleed = round($health * 0.6);

		/* Get Head Damage */
		$head_damage = round(($head / $health) * ($max_bleed * 0.5));
		/* Get Torso Damage */
		$torso_damage = round(($torso / $health) * ($max_bleed * 0.4));
		/* Get Right Arm */
		$upper_right_damage = round(($upper_right / $health) * ($max_bleed * 0.3));
		/* Get Left Arm */
		$upper_left_damage = round(($upper_left / $health) * ($max_bleed * 0.3));
		/* Get Right Leg */
		$lower_right_damage = round(($lower_right / $health) * ($max_bleed * 0.2));
		/* Get Left Left */
		$lower_left_damage = round(($lower_left / $health) * ($max_bleed * 0.2));

		$damage = $head_damage + $torso_damage + $upper_left_damage + $upper_right_damage + $lower_left_damage + $lower_right_damage;
		return $damage;
	} else { return 0; }
}


/* Heal Wounds */
function auto_heal($character_id) {
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$head = $row['head'];
		$torso = $row['torso'];
		$upper_left = $row['upper_left'];
		$upper_right = $row['upper_right'];
		$lower_left = $row['lower_left'];
		$lower_right = $row['lower_right'];
		$armor = $row['equippedArmor'];
		$health = $row['totalHealth'];
		$current_health = $row['cur_health'];
		$player_level = $row['level'];
	}

	$heal_amount = 1; //round($health * .01);

	if ($head >= $heal_amount) {
		$query = "UPDATE characters SET head=head-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET head=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	if ($torso >= $heal_amount) {
		$query = "UPDATE characters SET torso=torso-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET torso=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	if ($upper_left >= $heal_amount) {
		$query = "UPDATE characters SET upper_left=upper_left-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET upper_left=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	if ($upper_right >= $heal_amount) {
		$query = "UPDATE characters SET upper_right=upper_right-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET upper_right=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	if ($lower_left >= $heal_amount) {
		$query = "UPDATE characters SET lower_left=lower_left-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET lower_left=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
	if ($lower_right >= $heal_amount) {
		$query = "UPDATE characters SET lower_right=lower_right-$heal_amount WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$query = "UPDATE characters SET lower_right=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
	}
}
?>