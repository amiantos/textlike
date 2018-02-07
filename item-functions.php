<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

/* Generate a Weapon */
function generate_weapon($character_id,$level) {
	if ($level == 0) {
		$level = room_level(character_room($character_id));
		$level = $level + rand(-1,1);
		$critical = rand(1,120);
		if ($critical == 1) { $level = $character_level + 4; }
		if ($critical == 2) { $level = $character_level + 2; }
		if ($critical == 3) { $level = $character_level - 2; }
		if ($critical == 4) { $level = $character_level - 4; }
		if ($level < 1) { $level = 1; }
	}
	$level = $level + rand(-1,1);
	$critical = rand(1,120);
	if ($critical == 1) { $level = $character_level + 4; }
	if ($critical == 2) { $level = $character_level + 2; }
	if ($critical == 3) { $level = $character_level - 2; }
	if ($critical == 4) { $level = $character_level - 4; }
	if ($level < 1) { $level = 1; }
	/* Calculate Initial Damage */
	$damage = 20 + (($level - 1)*1.5);
	/* Calculate Initial Weight */
	$weight = 8 + (($level - 1)*0.3);
	/* Calculate Initial Durability */
	$durability = 45 + (($level - 1)*1);

	/* Determine Material */
	$rand_number = rand(1,100);
	if ($rand_number >= 0 & $rand_number <= 39) {
		$material = 1; $damage_with_material = $damage * 0.8; $durability_with_material = $durability * 0.8; $weight_with_material = $weight * 0.8; $material_name='Copper ';
	}
	if ($rand_number >= 40 & $rand_number <= 73) {
		$material = 2; $damage_with_material = $damage * 0.9; $durability_with_material = $durability * 0.9; $weight_with_material = $weight * 1.1; $material_name='Bronze ';
	}
	if ($rand_number >= 74 & $rand_number <= 89) {
		$material = 3; $damage_with_material = $damage * 1.0; $durability_with_material = $durability * 1;$weight_with_material = $weight * 1.2; $material_name='Iron ';
	}
	if ($rand_number >= 90 & $rand_number <= 100) {
		$material = 4; $damage_with_material = $damage * 1.1; $durability_with_material = $durability * 1.1; $weight_with_material = $weight * 0.9; $material_name='Steel ';
	}

	/* Determine Quality */
	$rand_number = rand(1,100);
	if ($rand_number >= 90 & $rand_number <= 100) {
		$quality = 5; /* Quality */ $total_damage = $damage_with_material * 1.05; $total_durability = $durability_with_material * 1.1; $total_weight = $weight_with_material * 0.8; $quality_name='Artisan ';
	}
	if ($rand_number >= 78 & $rand_number <= 89) {
		$quality = 4; /* Very Fine */ $total_damage = $damage_with_material * 1.025; $total_durability = $durability_with_material * 1.05; $total_weight = $weight_with_material * 0.9; $quality_name='Quality ';
	}
	if ($rand_number >= 40 & $rand_number <= 77) {
		$quality = 3; /* Good */ $total_damage = $damage_with_material; $total_durability = $durability_with_material * 1; $total_weight = $weight_with_material * 1; $quality_name='Fine ';
	}
	if ($rand_number >= 20 & $rand_number <= 39) {
		$quality = 2; /* Tarnished  */ $total_damage = $damage_with_material - ($damage_with_material * 0.025); $total_durability = $durability_with_material * 0.9; $total_weight = $weight_with_material * 1.1; $quality_name='Vintage ';
	}
	if ($rand_number >= 0 & $rand_number <= 19) {
		$quality = 1; /* Weak  */ $total_damage = $damage_with_material - ($damage_with_material * 0.05); $total_durability = $durability_with_material * 0.8; $total_weight = $weight_with_material * 1.1; $quality_name='Unbalanced ';
	}

	/* Randomly Select Kind of Weapon */
	$rand_number = rand(1,2);
	if ($rand_number == 1) { $weapon_kind = 'Sword'; }
	if ($rand_number == 2) { $weapon_kind = 'Axe'; }

	/* Build Weapon Name */
	$weapon_name = $quality_name.$material_name.$weapon_kind;

	/* Write Weapon To Table */
	$query = "INSERT INTO weapons (name,quality,material,damage,level,shortName,total_durability,current_durability,weight,created_for) VALUES ('$weapon_name','$quality','$material','$total_damage','$level','$weapon_kind','$total_durability','$total_durability','$total_weight','$character_id')";
	mysql_query($query) or die(mysql_error());
	/* Get and return that ID */
	$id = mysql_insert_id();
	return $id;
}

/* Drop Weapon In Room */
function place_weapon_in_room($item_id,$room_id) {
	$query = "UPDATE weapons SET inRoom='$room_id' WHERE id='$item_id'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Generate a Armor */
function generate_armor($character_id,$level) {
	if ($level == 0) {
		$level = room_level(character_room($character_id));
		$level = $level + rand(-1,1);
		$critical = rand(1,120);
		if ($critical == 1) { $level = $character_level + 4; }
		if ($critical == 2) { $level = $character_level + 2; }
		if ($critical == 3) { $level = $character_level - 2; }
		if ($critical == 4) { $level = $character_level - 4; }
		if ($level < 1) { $level = 1; }
	}
	$level = $level + rand(-1,1);
	$critical = rand(1,120);
	if ($critical == 1) { $level = $character_level + 4; }
	if ($critical == 2) { $level = $character_level + 2; }
	if ($critical == 3) { $level = $character_level - 2; }
	if ($critical == 4) { $level = $character_level - 4; }
	if ($level < 1) { $level = 1; }
	/* Calculate Initial Protection */
	$protection = 10 + (($level - 1) * 1.1);
	/* Calculate Initial Weight */
	$weight = 10 + (($level - 1)*1);
	/* Calculate Initial Durability */
	$durability = 55 + (($level - 1)*0.9);

	/* Determine Material */
	$rand_number = rand(1,100);
	if ($rand_number >= 0 & $rand_number <= 39) {
		$material = 1; $protection_with_material = $protection * 0.9; $weight_with_material = $weight * 0.9; $durability_with_material = $durability * 0.8; $material_name='Leather';
	}
	if ($rand_number >= 40 & $rand_number <= 73) {
		$material = 2; $protection_with_material = $protection * 1; $weight_with_material = $weight * 1.0; $durability_with_material = $durability * 1.0; $material_name='Chain';
	}
	if ($rand_number >= 74 & $rand_number <= 89) {
		$material = 3; $protection_with_material = $protection * 1.1; $weight_with_material = $weight * 1.1; $durability_with_material = $durability * 1.1; $material_name='Plate';
	}
	if ($rand_number >= 90 & $rand_number <= 100) {
		$material = 4; $protection_with_material = $protection * 1.2; $weight_with_material = $weight * 0.8; $durability_with_material = $durability * 0.9; $material_name='Dragon Scale';
	}

	/* Determine Quality */
	$rand_number = rand(1,100);
	if ($rand_number >= 90 & $rand_number <= 100) {
		$quality = 5; /* Quality */ $total_protection = $protection_with_material * 1.2; $total_weight = $weight_with_material * 0.8; $total_durability = $durability_with_material * 1.1; $quality_name='Divine ';
	}
	if ($rand_number >= 78 & $rand_number <= 89) {
		$quality = 4; /* Very Fine */ $total_protection = $protection_with_material * 1.1; $total_weight = $weight_with_material * 0.9; $total_durability = $durability_with_material * 1.05; $quality_name='Royal ';
	}
	if ($rand_number >= 40 & $rand_number <= 77) {
		$quality = 3; /* Good */ $total_protection = $protection_with_material; $total_weight = $weight_with_material * 1; $total_durability = $durability_with_material * 1; $quality_name='Classic ';
	}
	if ($rand_number >= 20 & $rand_number <= 39) {
		$quality = 2; /* Tarnished  */ $total_protection = $protection_with_material - ($protection_with_material * 0.1); $total_weight = $weight_with_material * 1; $total_durability = $durability_with_material * 0.9; $quality_name='Vintage ';
	}
	if ($rand_number >= 0 & $rand_number <= 19) {
		$quality = 1; /* Weak  */ $total_protection = $protection_with_material - ($protection_with_material * 0.2); $total_weight = $weight_with_material * 0.9;  $total_durability = $durability_with_material * 0.8; $quality_name='Ruined ';
	}

	/* Build Weapon Name */
	$armor_name_ex = " Armor";
	$short_name = $quality_name.$material_name;
	$armor_name = $quality_name.$material_name.$armor_name_ex;

	/* Write Armor To Table */
	$query = "INSERT INTO armors (name,quality,material,protect,level,shortName,weight,total_durability,current_durability,created_for) VALUES ('$armor_name','$quality','$material','$total_protection','$level','$short_name','$total_weight','$total_durability','$total_durability','$character_id')";
	mysql_query($query) or die(mysql_error());
	/* Get and return that ID */
	$id = mysql_insert_id();
	return $id;
}

/* Drop Armor In Room */
function place_armor_in_room($item_id,$room_id) {
	$query = "UPDATE armors SET inRoom='$room_id' WHERE id='$item_id'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Generate a Tome */
function generate_tome($character_id,$level) {
	if ($level == 0) {
		$level = room_level(character_room($character_id));
		$level = $level + rand(-1,1);
		$critical = rand(1,120);
		if ($critical == 1) { $level = $character_level + 4; }
		if ($critical == 2) { $level = $character_level + 2; }
		if ($critical == 3) { $level = $character_level - 2; }
		if ($critical == 4) { $level = $character_level - 4; }
		if ($level < 1) { $level = 1; }
	}
	$level = $level + rand(-1,1);
	$critical = rand(1,120);
	if ($critical == 1) { $level = $character_level + 4; }
	if ($critical == 2) { $level = $character_level + 2; }
	if ($critical == 3) { $level = $character_level - 2; }
	if ($critical == 4) { $level = $character_level - 4; }
	if ($level < 1) { $level = 1; }
	/* Calculate Initial Damage */
	$damage = 25 + (($level - 1)*1.5) + rand(1,5);
	/* Calculate Initial Weight */
	$weight = 1 + (($level - 1)*0.2);
	/* Calculate Initial Durability */
	$durability = 2 + (($level - 1)*0.05);

	/* Determine Element */
	$rand_number = rand(1,3);
	if ($rand_number == 1) {
		$material = 1; $damage_with_material = $damage * 1.0; $durability_with_material = $durability * 1.0; $weight_with_material = $weight * 1.0; $material_name='Frost';
	}
	if ($rand_number == 2) {
		$material = 2; $damage_with_material = $damage * 1.0; $durability_with_material = $durability * 1.0; $weight_with_material = $weight * 1.0; $material_name='Flame';
	}
	if ($rand_number == 3) {
		$material = 3; $damage_with_material = $damage * 1.0; $durability_with_material = $durability * 1.0;$weight_with_material = $weight * 1.0; $material_name='Rot';
	}
	$rand_number = rand(1,50);
	if ($rand_number == 1) {
		$material = 4; $damage_with_material = $damage * 1.5; $durability_with_material = $durability * 1.5; $weight_with_material = $weight * 1.0; $material_name='Unicorn';
	}

	/* Determine Quality */
	$total_damage = $damage_with_material;
	$total_durability = $durability_with_material;
	$total_weight = $weight_with_material;


	/* Build Weapon Name */
	$weapon_name = "Tome of $material_name";
	$weapon_kind = $material_name;

	/* Write Weapon To Table */
	$query = "INSERT INTO tomes (name,material,damage,level,shortName,total_durability,current_durability,weight,created_for) VALUES ('$weapon_name','$material','$total_damage','$level','$weapon_kind','$total_durability','$total_durability','$total_weight','$character_id')";
	mysql_query($query) or die(mysql_error());
	/* Get and return that ID */
	$id = mysql_insert_id();
	return $id;
}

/* Drop Tome In Room */
function place_tome_in_room($item_id,$room_id) {
	$query = "UPDATE tomes SET inRoom='$room_id' WHERE id='$item_id'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Generate an Item */
function generate_item($character_id) {
	/* load character level */
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$character_level = $row['level'];
	}
	/* Is it going to be a set of bandages or a health vial? */
	// $rand_number = rand(1,5);
	// if ($rand_number == 1) { $kind = 2; } else { $kind = 1; }
	$kind = 1;
	// disabling apples again

	$cur_apples = count_apples($character_id);
	$total_health = total_health($character_id);
	$current_health = current_health($character_id);
	// If the user has one apple or more and current health is greater than 15%, drop bandages instead of an apple
	// if ($cur_apples >= 1 && $kind == 2 && ($current_health > ($total_health/5))) { $kind = 1; }

	/* Write Item To Table */
	$query = "INSERT INTO items (kind,character_id,created_for) VALUES ('$kind','$character_id','$character_id')";
	mysql_query($query) or die(mysql_error());
	/* Get and return that ID */
	$id = mysql_insert_id();
	return $id;
}

/* Drop Item In Room */
function place_item_in_room($item_id,$room_id) {
	$query = "UPDATE items SET inRoom='$room_id' WHERE id='$item_id'";
	$result = mysql_query($query) or die(mysql_error());
}

/* Generate and Place a Chest */
function generate_chest($current_room,$special,$character_id) {
	if ($special) {
		$query = "INSERT INTO structures (type,name,inRoom,created_for) VALUES ('1','Glowing Chest','$current_room','$character_id')";
		mysql_query($query) or die(mysql_error());
	} else if (rand(0,100) == 5) {
			$what = rand(1,3);
			if ($what == 1) { $word = "Glowing"; } else if ($what == 2) { $word = "Vibrating"; } else { $word = "Singing"; }
			$query = "INSERT INTO structures (type,name,inRoom,created_for) VALUES ('1','$word Chest','$current_room','$character_id')";
			mysql_query($query) or die(mysql_error());
		} else {
		$query = "INSERT INTO structures (type,name,inRoom,created_for) VALUES ('2','Chest','$current_room','$character_id')";
		mysql_query($query) or die(mysql_error());
	}
	/* Get and return that ID
$id = mysql_insert_id();
return $id; */
}

/* Open A Chest */
function open_chest($chest_id,$character_id) {
	/* Load Chest Info To Receive Floor And Type */
	$query = "SELECT * FROM structures WHERE (id='$chest_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$room = $row['inRoom'];
	}
	if ($type == 1) {
		/* Special Chest */
		place_weapon_in_room(generate_weapon($character_id,0),$room);
		place_weapon_in_room(generate_weapon($character_id,0),$room);
		place_weapon_in_room(generate_weapon($character_id,0),$room);
		$bandages_total = count_bandages($character_id);
		if ($bandages_total < 1) {
			place_item_in_room(generate_item($character_id),$room);
			place_item_in_room(generate_item($character_id),$room);
			place_item_in_room(generate_item($character_id),$room);
		} else {
			place_item_in_room(generate_item($character_id),$room);
			place_item_in_room(generate_item($character_id),$room);
		}
		place_armor_in_room(generate_armor($character_id,0),$room);
		place_armor_in_room(generate_armor($character_id,0),$room);
		place_armor_in_room(generate_armor($character_id,0),$room);
		place_tome_in_room(generate_tome($character_id,0),$room);
		place_tome_in_room(generate_tome($character_id,0),$room);
		place_tome_in_room(generate_tome($character_id,0),$room);
		$total_items = 9;
	} else if ( $type == 2 ) {
			/* Normal Chest, Randomized */


			place_weapon_in_room(generate_weapon($character_id,0),$room);
			place_armor_in_room(generate_armor($character_id,0),$room);
			if (rand(0,4) == 1) { place_weapon_in_room(generate_weapon($character_id,0),$room); }
			if (rand(0,4) == 1) { place_armor_in_room(generate_armor($character_id,0),$room); }
			if (rand(0,40) == 1) { place_weapon_in_room(generate_weapon($character_id,0),$room); }
			if (rand(0,40) == 1) { place_armor_in_room(generate_armor($character_id,0),$room); }

			/* Place 3 random healing item? */
			
			if (rand(0,2) == 1) { place_item_in_room(generate_item($character_id),$room);$items = $items + 1; }
			if (rand(0,2) == 1) { place_item_in_room(generate_item($character_id),$room);$items = $items + 1; }
			if (rand(0,4) == 1) { place_item_in_room(generate_item($character_id),$room);$items = $items + 1; }
			if (rand(0,10) == 1) { place_item_in_room(generate_item($character_id),$room);$items = $items + 1; }
			
			// Place a tome or two maybe?
			if (rand(0,4)==1) { place_tome_in_room(generate_tome($character_id,0),$room);}
			if (rand(0,7)==1) { place_tome_in_room(generate_tome($character_id,0),$room);}
			if (rand(0,10)==1) { place_tome_in_room(generate_tome($character_id,0),$room);}

		} else {
		echo "Error in Chest Opener: Not A Chest!";
		exit;
	}
	/* Delete Chest After Opening */
	$query = "DELETE FROM structures WHERE id='$chest_id'";
	$result = mysql_query($query) or die(mysql_error());
	/* Update Room History */
	if ($total_items == 1) { $were = 'was'; } else { $were = 'were'; }
	$history = "You opened a chest; there ".$were." ".$total_items." items inside.";
	write_room_history($history,$room,$character_id);
}

/* Load Chests Into Room */
function load_chests($current_room,$current_character_id,$user) {
	$query = "SELECT * FROM structures WHERE (inRoom='$current_room')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$chest_id = $row['id'];
		echo "<P class='room-desc'>There is a ".strtolower($name)." here. <a href='processing.php?openchest=".$chest_id."&character=".$current_character_id."&user=".$user."'>Open it</a>?</P>";
	}
}

/* Load Weapons Into Room */
function load_weapons($current_room,$current_character_id,$user) {
	$query = "SELECT * FROM weapons WHERE (inRoom='$current_room') ORDER BY damage DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo "There's a ";
		} else if ($numrows == 2) {
			echo "There's a ";
		} else if ($numrows >= 3) {
			echo "In a pile on the floor, you see a ";
		}
	$current_number = 1;
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$durability = $row['current_durability'];
		$weap_id = $row['id'];
		if ($current_number == $numrows && $numrows > 1) { echo " and a ";}
		if ($durability == 0) {
			echo "<span class='weapon'>broken ".strtolower(display_weapon($weap_id))."</a></span>";
		} else {
			echo "<span class='weapon'><a href='processing.php?pickup=".$weap_id."&character=".$current_character_id."&user=".$user."'>".display_weapon($weap_id)."</a> ".display_weapon_stats($weap_id)."</span>";
		}
		if ($current_number < $numrows) { echo ", ";}
		$current_number = $current_number +1;
	}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo " lying on the floor. ";
		} else if ($numrows == 2) {
			echo " on the floor. ";
		} else if ($numrows >= 3) {
			echo ". ";
		}

}

/* Load Tomes Into Room */
function load_tomes($current_room,$current_character_id,$user) {
	$query = "SELECT * FROM tomes WHERE (inRoom='$current_room') AND (current_durability > 0) ORDER BY damage DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo "On a shelf, there's a ";
		} else if ($numrows == 2) {
			echo "On a shelf, there's a ";
		} else if ($numrows >= 3) {
			echo "On a shelf, you see a ";
		}
	$current_number = 1;
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$durability = $row['current_durability'];
		$weap_id = $row['id'];
		if ($current_number == $numrows && $numrows > 1) { echo " and a ";}
		if ($durability == 0) {
			//echo "<span class='tome'>destroyed ".strtolower(display_tome($weap_id))."</a></span>";
		} else {
			echo "<span class='tome'><a href='processing.php?pickupt=".$weap_id."&character=".$current_character_id."&user=".$user."'>".display_tome($weap_id)."</a> ".display_tome_stats($weap_id)."</span>";
		}
		if ($current_number < $numrows) { echo ", ";}
		$current_number = $current_number +1;
	}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo ". ";
		} else if ($numrows == 2) {
			echo ". ";
		} else if ($numrows >= 3) {
			echo ". ";
		}

}

/* Load Armor Into Room */
function load_armor($current_room,$current_character_id,$user) {
	$query = "SELECT * FROM armors WHERE (inRoom='$current_room') ORDER BY protect DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo "There's a ";
		} else if ($numrows == 2) {
			echo "There are ";
		} else if ($numrows >= 3) {
			echo "Scattered around the room are ";
		}
	$current_number = 1;
	while($row = mysql_fetch_array($result)) {
		$armor_id = $row['id'];
		$durability = $row['current_durability'];
		if ($current_number == $numrows && $numrows > 1) { echo " and ";}
		if ($durability == 0) {
			echo "<span class='armor'>broken ".strtolower(display_armor($armor_id))."</span>";
		} else {
			echo "<span class='armor'><a href='processing.php?pickupar=".$armor_id."&character=".$current_character_id."&user=".$user."'>".display_armor($armor_id)."</a> ".display_armor_stats($armor_id)."</span>";
		}
		if ($current_number < $numrows) { echo ", ";}
		$current_number = $current_number +1;
	}
	if ($numrows == 0) {

	} else if ($numrows == 1) {
			echo " armor lying on the floor. ";
		} else if ($numrows == 2) {
			echo " armors on the floor. ";
		} else if ($numrows >= 3) {
			echo " armors. ";
		}
}

/* Load Items Into Room */
function load_items($current_room,$current_character_id,$user) {
	$query = "SELECT * FROM items WHERE (inRoom='$current_room')";
	$result = mysql_query($query) or die(mysql_error());
	$bandages_amount = 0;
	$apples_amount = 0;
	$numrows=mysql_num_rows($result);
	while($row = mysql_fetch_array($result)) {
		$kind = $row['kind'];
		$item_id = $row['id'];
		if ($kind == 1) { $bandages_amount++; }
		if ($kind == 2) {$apples_amount++; }
	}
	if ($bandages_amount > 0) {
		$name = "bandages";
		if ($bandages_amount==1) {
			$are = "is a single roll of";
		} else if ($bandages_amount==2) {
				$are = "is a pair of";
			} else {
			$are = "is a pile of";
		}
		if ($numrows>0){
			if (count_bandages($current_character_id) >= 6) {
				echo "<span class='healing'>There ".$are." ".$name." here.</span> ";
			} else {
				echo "<span class='healing'>There ".$are." <a href='processing.php?pickupitems=".$current_room."&character=".$current_character_id."&user=".$user."'>".$name."</a> here.</span> ";
			}
		}
	}
	if ($apples_amount > 0) {
		if ($apples_amount==1) {
			$are = "is a single";
			$name = "apple";
		} else if ($apples_amount==2) {
				$are = "is a pair of";
				$name = "apples";
			} else {
			$are = "is a pile of";
			$name = "apples";
		}
		if ($numrows>0){
			if (count_apples($current_character_id) >= 2) {
				echo "<span class='healing'>There ".$are." ".$name." here.</span><br/>";
			} else {
				echo "<span class='healing'>There ".$are." <a href='processing.php?pickupitems=".$current_room."&character=".$current_character_id."&user=".$user."'>".$name."</a> here.</span><br/>";
			}
		}
	}


}

/* Return Equipped Weapon ID */
function return_equipped_weapon_id($current_character_id) {
	$query = "SELECT * FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$weapon = $row['equippedWeapon'];
	}
	return $weapon;
}

/* Return Equipped tome ID */
function return_equipped_tome_id($current_character_id) {
	$query = "SELECT * FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$tome = $row['equippedTome'];
	}
	return $tome;
}

/* Return Equipped Armor ID */
function return_equipped_armor_id($current_character_id) {
	$query = "SELECT * FROM characters WHERE (id='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$armor = $row['equippedArmor'];
	}
	return $armor;
}

/* List Weapons (Inventory) and Allow Equip */
function display_inventory_weapons($current_character_id,$current_character_id,$user) {
	$currently_equipped = return_equipped_weapon_id($current_character_id);
	$query = "SELECT * FROM weapons WHERE (characterid='$current_character_id') ORDER BY damage DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) { echo ""; }
	while($row = mysql_fetch_array($result)) {
		$weap_id = $row['id'];
		if ($currently_equipped != $weap_id) {
			echo "<tr>";
			if (weapon_durability($weap_id) > 0) {echo "<td style='border-right:1px solid #808080;padding-right:10px;'><a href='processing.php?equip=".$weap_id."&character=".$current_character_id."&user=".$user."'>Equip</a>&nbsp;|&nbsp;<a href='processing.php?drop=".$weap_id."&type=weapon&character=".$current_character_id."&user=".$user."'>Drop</a></td>";}
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_weapon($weap_id)."</td><td align='right' style='padding-left:10px;'>".display_weapon_stats($weap_id)."</td>";
			echo "</tr>";
		} else {
			echo "<tr>";
			if (weapon_durability($weap_id) > 0) {echo "<td style='border-right:1px solid #808080;padding-right:10px;'><center>Right&nbsp;Hand</td>";}
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_weapon($weap_id)."</td><td align='right' style='padding-left:10px;'>".display_weapon_stats($weap_id)."</td>";
			echo "</tr>";
		}
	}
	echo "";
}

/* List Tomes (Inventory) and Allow Equip */
function display_inventory_tomes($current_character_id,$current_character_id,$user) {
	$currently_equipped = return_equipped_tome_id($current_character_id);
	$query = "SELECT * FROM tomes WHERE (characterid='$current_character_id') ORDER BY damage DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) { echo ""; }
	while($row = mysql_fetch_array($result)) {
		$weap_id = $row['id'];
		if ($currently_equipped != $weap_id) {
			echo "<tr>";
			echo "<td style='border-right:1px solid #808080;padding-right:10px;'><a href='processing.php?equipt=".$weap_id."&character=".$current_character_id."&user=".$user."'>Equip</a>&nbsp;|&nbsp;<a href='processing.php?drop=".$weap_id."&type=tome&character=".$current_character_id."&user=".$user."'>Drop</a></td>";
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_tome($weap_id)."</td><td align='right' style='padding-left:10px;'>".display_tome_stats($weap_id)."</td>";
			echo "</tr>";
		} else {
			echo "<tr>";
			echo "<td style='border-right:1px solid #808080;padding-right:10px;'><center>Left&nbsp;Hand</td>";
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_tome($weap_id)."</td><td align='right' style='padding-left:10px;'>".display_tome_stats($weap_id)."</td>";
			echo "</tr>";
		}
	}
	echo "";
}

/* List Armors (Inventory) and Allow Equip */
function display_inventory_armors($current_character_id,$current_character_id,$user) {
	$currently_equipped = return_equipped_armor_id($current_character_id);
	$query = "SELECT * FROM armors WHERE (characterid='$current_character_id') ORDER BY protect DESC,current_durability DESC,weight ASC";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) { echo ""; }
	while($row = mysql_fetch_array($result)) {
		$armor_id = $row['id'];
		if ($currently_equipped != $armor_id) {
			echo "<tr>";
			if (armor_durability($armor_id) > 0) { echo "<td style='border-right:1px solid #808080;padding-right:10px;'><a href='processing.php?equipar=".$armor_id."&character=".$current_character_id."&user=".$user."'>Equip</a>&nbsp;|&nbsp;<a href='processing.php?drop=".$armor_id."&type=armor&character=".$current_character_id."&user=".$user."'>Drop</a></td>"; }
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_armor($armor_id)."</td><td align='right' style='padding-left:10px;'>".display_armor_stats($armor_id)."</td>";
		} else {
			echo "<tr>";
			if (armor_durability($armor_id) > 0) { echo "<td style='border-right:1px solid #808080;padding-right:10px;'><center>Worn</td>"; }
			echo "<td style='border-right:1px solid #808080;padding-left:10px;width:100%;' align='center'>".display_armor($armor_id)."</td><td align='right' style='padding-left:10px;'>".display_armor_stats($armor_id)."</td>";
			echo "</tr>";
		}
	}
	echo "";
}

/* List Items (Inventory) and Allow Use */
function display_inventory_items($current_character_id,$current_character_id,$user) {
	$attacking = get_victim($current_character_id);
	$query = "SELECT * FROM items WHERE (possessed='$current_character_id') GROUP BY kind";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	echo "<h3>Items</h3><P>";
	if ($numrows == 0) { echo "None :("; }
	while($row = mysql_fetch_array($result)) {
		$kind = $row['kind'];
		$item_id = $row['id'];
		$query2 = "SELECT * FROM items WHERE (possessed='$current_character_id') and (kind='$kind')";
		$result2 = mysql_query($query2) or die(mysql_error());
		$numrows2=mysql_num_rows($result2);
		if ($kind == 1) { $name = "Bandages"; }
		if ($kind == 2) { $name = "Apples"; }
		$total_health = total_health($current_character_id);
		$current_health = current_health($current_character_id);
		if (is_bleeding($current_character_id) > 0 && $kind == 1) {
			echo "| <a href='processing.php?useit=".$item_id."&character=".$current_character_id."&user=".$user."'>Use</a> |";
		}
		else if (is_bleeding($current_character_id) != 1 && $kind == 1) { echo "| Use |"; }
		else if ($current_health >= ($total_health-($total_health*0.25)) && $kind == 2 || $attacking > 0) {
				echo "| Use |";
			}
		else {
			echo "| <a href='processing.php?useit=".$item_id."&character=".$current_character_id."&user=".$user."'>Use</a> |";
		}
		echo " ".$name." (".$numrows2." left)<br/>";
	}
	echo "</p>";
}

/* List Items (Inventory) and Allow Use BAR VERSION */
function display_inventory_items_bar($current_character_id,$current_character_id,$user) {
	$attacking = get_victim($current_character_id);
	$query = "SELECT * FROM items WHERE (possessed='$current_character_id') GROUP BY kind";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	while($row = mysql_fetch_array($result)) {
		$kind = $row['kind'];
		$item_id = $row['id'];
		$query2 = "SELECT * FROM items WHERE (possessed='$current_character_id') and (kind='$kind')";
		$result2 = mysql_query($query2) or die(mysql_error());
		$numrows2=mysql_num_rows($result2);
		if ($kind == 1) { $name = "Bandage"; }
		if ($kind == 2) { $name = "Apple"; }
		$total_health = total_health($current_character_id);
		$current_health = current_health($current_character_id);
		if ($kind == 1 && $numrows2 > 0) { $bandages = 1; }
		if ($kind == 2 && $numrows2 > 0 && $bandages > 0) { echo " &middot; ";}
		if (is_bleeding($current_character_id) > 0 && $kind == 1) {
			echo "<a href='processing.php?useit=".$item_id."&character=".$current_character_id."&user=".$user."' class='menuitem'>";
		}
		else if (is_bleeding($current_character_id) != 1 && $kind == 1) { echo ""; }
		else if ($current_health >= ($total_health-($total_health*0.50)) && $kind == 2 || $attacking > 0) {
				echo "";
			}
		else {
			echo "<a href='processing.php?useit=".$item_id."&character=".$current_character_id."&user=".$user."' class='menuitem'>";
		}
		echo "".$name."</a> (".$numrows2.")";
	}
	if ($numrows == 0) { echo "<div style='display:inline;'>&nbsp;</div>";}
}

/* Count Bandages */
function count_bandages($current_character_id) {
	$attacking = get_victim($current_character_id);
	$query = "SELECT * FROM items WHERE (possessed='$current_character_id') AND (kind='1')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	return $numrows;
}

/* Count Apples */
function count_apples($current_character_id) {
	$attacking = get_victim($current_character_id);
	$query = "SELECT * FROM items WHERE (possessed='$current_character_id') AND (kind='2')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	return $numrows;
}

/* Use Item Function */
function use_item($item,$character_id) {
	/* Load Character Info */
	$query = "SELECT * FROM characters WHERE (id='$character_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$total_health = $row['totalHealth'];
		$current_health = $row['cur_health'];
		$room = $row['inRoom'];
	}
	/* Load Item Info */
	$query = "SELECT * FROM items WHERE (possessed='$character_id') AND (id='$item')";
	$result = mysql_query($query) or die(mysql_error());
	$numrows=mysql_num_rows($result);
	if ($numrows == 0) { echo "Item does not exist or does not belong to you."; exit; }
	while($row = mysql_fetch_array($result)) {
		$kind = $row['kind'];
	}
	/* If kind is 1, it's bandages */
	if ($kind == 1) {
		/* Heal Wounds */
		$query = "UPDATE characters SET head=0,torso=0,upper_left=0,upper_right=0,lower_left=0,lower_right=0 WHERE id='$character_id'";
		mysql_query($query) or die(mysql_error());
		/* If attacking, update battle log */
		$attacking = get_victim($character_id);
		if ($attacking) {
			$turns = num_of_turns($character_id);
			$history = "You\'ve bandaged your wounds and stopped the bleeding.";
			write_battle_history($history,$attacking,$character_id,'bandage',$turns,0,0);
		} else {
			/* Update Room History */
			$history = "You\'ve bandaged your wounds and stopped the bleeding.";
			write_room_history($history,$room,$character_id);
		}
		end_turn($character_id);
	}
	/* If kind is 2, it's an apple */
	else if ($kind == 2) {
			/* If current health is equal to or greater than total health, do nothing */
			if ($current_health >= $total_health) {
				/* If attacking, update battle log */
				$attacking = get_victim($character_id);
				if ($attacking) {
					$history = "You ate an apple, to no effect.";
					write_battle_history($history,$attacking,$character_id,'apple',0,0,0);
				} else {
					/* Update Room History */
					$history = "You ate an apple, to no effect.";
					write_room_history($history,$room,$character_id);
				}
			} else {
				/* Heal Damage */
				$heal_amount = $total_health;
				$healed_amount = $current_health + $heal_amount;
				if ($healed_amount > $total_health) {$healed_amount = $total_health;}
				$query = "UPDATE characters SET cur_health=$healed_amount WHERE id='$character_id'";
				mysql_query($query) or die(mysql_error());
				/* If attacking, update battle log */
				$attacking = get_victim($character_id);
				if ($attacking) {
					$turns = num_of_turns($character_id);
					$history = "You ate an apple, which you shouldn't be able to do, cheater.";
					write_battle_history($history,$attacking,$character_id,'apple-cheater',$turns,0,0);
				} else {
					/* Update Room History */
					$rand = rand(0,6);
					if ($rand == 0) { $history = "You ate an apple, and closed your eyes, and felt like you were home."; }
					if ($rand == 1) { $history = "While you ate the apple, you dreamed of her face."; }
					if ($rand == 2) { $history = "You tried to smile while you bit into the juicy red apple, but your mouth wouldn\'t make the shape."; }
					if ($rand == 3) { $history = "The crunch of the apple echoes off the four walls of this room."; }
					if ($rand == 4) { $history = "You don\'t know why just an apple makes you feel better, but you\'re glad it does."; }
					if ($rand == 5) { $history = "Every apple is a gift, or a fortunate accident to ofset the unfortunate ones."; }
					if ($rand == 6) { $history = "No apple has ever tasted as good as this one does right now."; }
					write_room_history($history,$room,$character_id);
				}
			}
			end_turn($character_id);
		}
	/* If kind is something else, throw error and die! */
	else { echo "Unknown kind of item!"; exit; }

	/* Delete Item After Use */
	$query = "DELETE FROM items WHERE (id='$item')";
	$result = mysql_query($query) or die(mysql_error());


}

/* Display Equipped Armor */
function display_equipped_armor($current_character_id,$current_character_id,$user) {
	$currently_equipped = return_equipped_armor_id($current_character_id);
	echo display_armor($currently_equipped)." ".display_armor_stats($currently_equipped);
	if ($currently_equipped == 0) { echo "No Armor"; }
}

/* Display Equipped Weapon */
function display_equipped_weapon($current_character_id,$current_character_id,$user) {
	$weapon_id = return_equipped_weapon_id($current_character_id);
	echo display_weapon($weapon_id)." ".display_weapon_stats($weapon_id);
	if ($weapon_id == 0) { echo "No Weapon"; }
}

/* Display Weapon Title Function */
function display_weapon($weapon_id) {
	$query = "SELECT * FROM weapons WHERE (id='$weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$weap_id = $row['id'];
		$string = $name;
	}
	return $string;
}

/* Display Weapon Stats Function */
function display_weapon_stats($weapon_id) {
	$query = "SELECT * FROM weapons WHERE (id='$weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$weap_id = $row['id'];
		$string = "(<span style='color:red;' title='Damage: ".$damage."'>".$damage."</span>/<span style='color:blue;' title='Total Durability: ".$total_durability."'>".$current_durability."</span>/<span style='color:orange;' title='Weight: ".$weight."'>".$weight."</span>)";
	}
	return $string;
}

/* Display Tome Title Function */
function display_tome($weapon_id) {
	$query = "SELECT * FROM tomes WHERE (id='$weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$weap_id = $row['id'];
		$string = $name;
	}
	return $string;
}

/* Display Tome Stats Function */
function display_tome_stats($weapon_id) {
	$query = "SELECT * FROM tomes WHERE (id='$weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$type = $row['type'];
		$name = $row['name'];
		$level = $row['level'];
		$damage = $row['damage'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$weap_id = $row['id'];
		$string = "(<span style='color:purple;' title='Damage: ".$damage."'>".$damage."</span>/<span style='color:blue;' title='Total Durability: ".$total_durability."'>".$current_durability."</span>/<span style='color:orange;' title='Weight: ".$weight."'>".$weight."</span>)";
	}
	return $string;
}

/* Display Armor Title Function */
function display_armor($armor_id) {
	$query = "SELECT * FROM armors WHERE (id='$armor_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$name = $row['shortName'];
		$level = $row['level'];
		$protect = $row['protect'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$string = $name;
	}
	return $string;
}

/* Display Armor Stats Function */
function display_armor_stats($armor_id) {
	$query = "SELECT * FROM armors WHERE (id='$armor_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$name = $row['shortName'];
		$level = $row['level'];
		$protect = $row['protect'];
		$total_durability = $row['total_durability'];
		$current_durability = $row['current_durability'];
		$weight = $row['weight'];
		$string = "(<span style='color:green;' title='Protection: ".$protect."'>".$protect."</span>/<span style='color:blue;' title='Total Durability: ".$total_durability."'>".$current_durability."</span>/<span style='color:orange;' title='Weight: ".$weight."'>".$weight."</span>)";
	}
	return $string;
}

/* Get Weapon Durability Function */
function weapon_durability($weapon_id) {
	$query = "SELECT * FROM weapons WHERE (id='$weapon_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$current_durability = $row['current_durability'];
	}
	return $current_durability;
}

/* Get Armor Durability Function */
function armor_durability($armor_id) {
	$query = "SELECT * FROM armors WHERE (id='$armor_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$current_durability = $row['current_durability'];
	}
	return $current_durability;
}

/* Get Armor Durability Function */
function tome_durability($armor_id) {
	$query = "SELECT * FROM tomes WHERE (id='$armor_id')";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)) {
		$current_durability = $row['current_durability'];
	}
	return $current_durability;
}

/* Are Equipped Items Broken? */
function are_equipped_items_broken($character_id) {
	$armor_id = return_equipped_armor_id($character_id);
	$weapon_id = return_equipped_weapon_id($character_id);
	$tome_id = return_equipped_tome_id($character_id);
	$current_room = character_room($character_id);
	$turns = num_of_turns($character_id);
	if ($weapon_id != '0') {
		/* Check Weapon Durability*/
		$weapon_dur = weapon_durability($weapon_id);
		if ($weapon_dur <= '0') {
			$query = "UPDATE characters SET equippedWeapon=0 WHERE id='$character_id'";
			mysql_query($query) or die(mysql_error());
			/* drop weapon in room */
			$query = "UPDATE weapons SET characterid='0',inRoom='$current_room' WHERE id='$weapon_id'";
			mysql_query($query) or die(mysql_error());
			/* If attacking, update battle log */
			$attacking = get_victim($character_id);
			if ($attacking) {
				$history = "Your weapon has broken and fallen from your hands.";
				write_battle_history($history,$attacking,$character_id,'weapon',$turns,0,0);
			}
		}
	}
	if ($armor_id != '0') {
		/* Check Weapon Durability*/
		$armor_dur = armor_durability($armor_id);
		if ($armor_dur <= '0') {
			$query = "UPDATE characters SET equippedArmor=0 WHERE id='$character_id'";
			mysql_query($query) or die(mysql_error());
			/* drop weapon in room */
			$query = "UPDATE armors SET characterid='0',inRoom='$current_room' WHERE id='$armor_id'";
			mysql_query($query) or die(mysql_error());
			/* If attacking, update battle log */
			$attacking = get_victim($character_id);
			if ($attacking) {
				$history = "Your armor has been reduced to scraps.";
				write_battle_history($history,$attacking,$character_id,'armor',$turns,0,0);
			}
		}
	}
	if ($tome_id != '0') {
		/* Check tome Durability*/
		$tome_dur = tome_durability($tome_id);
		if ($tome_dur <= '0') {
			$query = "UPDATE characters SET equippedTome=0 WHERE id='$character_id'";
			mysql_query($query) or die(mysql_error());
			/* drop weapon in room */
			$query = "UPDATE tomes SET characterid='0',inRoom='$current_room' WHERE id='$tome_id'";
			mysql_query($query) or die(mysql_error());
			/* If attacking, update battle log */
			$attacking = get_victim($character_id);
			if ($attacking) {
				$history = "Your tome has crumbled to dust.";
				write_battle_history($history,$attacking,$character_id,'tome',$turns,0,0);
			}
		}
	}
}

/* Damage Weapon */
function damage_weapon($weapon_id) {
	$query = "UPDATE weapons SET current_durability=current_durability-1 WHERE id='$weapon_id'";
	mysql_query($query) or die(mysql_error());
}

/* Damage tome */
function damage_tome($weapon_id) {
	$query = "UPDATE tomes SET current_durability=current_durability-1 WHERE id='$weapon_id'";
	mysql_query($query) or die(mysql_error());
}

/* Damage Armor */
function damage_armor($armor_id) {
	$query = "UPDATE armors SET current_durability=current_durability-1 WHERE id='$armor_id'";
	mysql_query($query) or die(mysql_error());
}




?>