<?php include('header.php'); 
$current_user = $_SESSION['usr'];
$current_character_id = selected_character_id($current_user);
$current_character = selected_character($current_character_id);
$available_points = available_points($current_character_id);
$current_room = character_room($current_character_id);
$current_room_level = room_level($current_room);
$num_of_turns = num_of_turns($current_character_id);
$attacking = get_victim($current_character_id);
$bandages_count = count_bandages($current_character_id);
$apples_count = count_apples($current_character_id);
?>
<?php
/* Create Character */
if ($_GET['character-name'] && $_GET['user']) {
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	$character = mysql_real_escape_string(htmlspecialchars($_GET['character-name']));
	$query = "INSERT INTO characters (created,user,name) VALUES ('$date','$user','$character')";
	mysql_query($query) or die(mysql_error());
	$current_character_id = mysql_insert_id();
	refresh_health_total($current_character_id);
	refresh_carry_total($current_character_id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Select Character */
if ($_GET['character-select'] && $_GET['user']) {
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	$character = mysql_real_escape_string($_GET['character-select']);
	$query = "UPDATE tz_members SET characterSelected='$character' WHERE usr='$user'";
	mysql_query($query) or die(mysql_error());
	$current_character_id = selected_character_id($current_user);
	header("Location: index.php");
mysql_close();
	exit;
}
/* De-select Character */
if ($_GET['clear']) {
	$user = mysql_real_escape_string($_GET['clear']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	$query = "UPDATE tz_members SET characterSelected='' WHERE usr='$user'";
	mysql_query($query) or die(mysql_error());
	header("Location: index.php");
mysql_close();
	exit;
}
/* Add Skill Point */
if ($_GET['add-point'] && $_GET['user'] && $_GET['id']) {
	$user = mysql_real_escape_string($_GET['user']);
	$add_point = mysql_real_escape_string($_GET['add-point']);
	$id = mysql_real_escape_string($_GET['id']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($id != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	/* Check To Make Sure Point Is Available */
	$available_points = available_points($current_character_id);
	if ($available_points < 1) { echo "Error! Points Not Available!"; exit; }
	
	if ($add_point == 'strength') {
	$query = "UPDATE characters SET strength=strength+1,availablePoints=availablePoints-1 WHERE id='$id'";
	}
	if ($add_point == 'dexterity') {
	$query = "UPDATE characters SET dexterity=dexterity+1,availablePoints=availablePoints-1 WHERE id='$id'";
	}
	if ($add_point == 'intelligence') {
	$query = "UPDATE characters SET intelligence=intelligence+1,availablePoints=availablePoints-1 WHERE id='$id'";
	}
	if ($add_point == 'stamina') {
	$query = "UPDATE characters SET stamina=stamina+1,availablePoints=availablePoints-1 WHERE id='$id'";
	}
	if ($add_point == 'luck') {
	$query = "UPDATE characters SET luck=luck+1,availablePoints=availablePoints-1 WHERE id='$id'";
	}
	mysql_query($query) or die(mysql_error());
	if ($add_point == 'stamina') {refresh_health_total_special($id);}
	if ($add_point == 'strength') {refresh_carry_total($id);}
	end_turn($id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Character Room Movement */
if ($_GET['move-char'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$moveid = mysql_real_escape_string($_GET['move-char']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
$query = "UPDATE characters SET inRoom='$moveid',lastRoom='$current_room' WHERE id='$characterid'";
mysql_query($query) or die(mysql_error());
end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Open Chest */
if ($_GET['openchest'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$chest = mysql_real_escape_string($_GET['openchest']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	open_chest($chest,$characterid);
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Pickup Weapon */
if ($_GET['pickup'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$weapon = mysql_real_escape_string($_GET['pickup']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE weapons SET characterid='$characterid',inRoom='0' WHERE id='$weapon'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Pickup tome */
if ($_GET['pickupt'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$weapon = mysql_real_escape_string($_GET['pickupt']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE tomes SET characterid='$characterid',inRoom='0' WHERE id='$weapon'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Pickup Armor */
if ($_GET['pickupar'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$armor = mysql_real_escape_string($_GET['pickupar']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE armors SET characterid='$characterid',inRoom='0' WHERE id='$armor'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Pickup Item */
if ($_GET['pickupit'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$item = mysql_real_escape_string($_GET['pickupit']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE items SET possessed='$characterid',inRoom='0' WHERE id='$item'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* New Pickup Items */
if ($_GET['pickupitems'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$room_id = mysql_real_escape_string($_GET['pickupitems']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	/* Check number of carried bandages */
	$item_difference = 6 - $bandages_count;
	while ($item_difference > 0) {
		$query = "UPDATE items SET possessed='$characterid',inRoom='0' WHERE inRoom='$room_id' AND kind='1' LIMIT 1";
		mysql_query($query) or die(mysql_error());
		$item_difference = $item_difference - 1;
	}
	/* Check number of carried apples */
	$item_difference = 2 - $apples_count;
	while ($item_difference > 0) {
		$query = "UPDATE items SET possessed='$characterid',inRoom='0' WHERE inRoom='$room_id' AND kind='2' LIMIT 1";
		mysql_query($query) or die(mysql_error());
		$item_difference = $item_difference - 1;
	}
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Equip Weapon */
if ($_GET['equip'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$weapon = mysql_real_escape_string($_GET['equip']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	if (weapon_durability($weapon) == 0) { echo "Error! Item broken!"; exit; }
	$query = "UPDATE characters SET equippedWeapon='$weapon' WHERE id='$characterid'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Equip tome */
if ($_GET['equipt'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$weapon = mysql_real_escape_string($_GET['equipt']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE characters SET equippedTome='$weapon' WHERE id='$characterid'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
	mysql_close();
	exit;
}
/* Equip Armor */
if ($_GET['equipar'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$armor = mysql_real_escape_string($_GET['equipar']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	$query = "UPDATE characters SET equippedArmor='$armor' WHERE id='$characterid'";
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Use Item */
if ($_GET['useit'] && $_GET['character'] && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$item = mysql_real_escape_string($_GET['useit']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	use_item($item,$characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Begin Attack */
if ($_GET['startattack'] && $_GET['character']  && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$mob = mysql_real_escape_string($_GET['startattack']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	//end_turn($characterid);
	$query = "UPDATE characters SET attacking='$mob' WHERE id='$characterid'";
	mysql_query($query) or die(mysql_error());
	/* Initialize Battle */
	init_battle($mob,$characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Defend Option */
if ($_GET['defend']) {
	player_defend($attacking,$current_character_id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Attack Option */
if ($_GET['attack']) {
	player_attack($attacking,$current_character_id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Tome Option */
if ($_GET['tome']) {
	player_tome($attacking,$current_character_id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Sub High Score and Delete */
if ($_GET['subdel']) {
	submit_and_delete($current_character_id);
	header("Location: index.php");
mysql_close();
	exit;
}
/* Drop Item */
if ($_GET['drop'] && $_GET['character'] && $_GET['type'] && $_GET['user']) {
	$characterid = mysql_real_escape_string($_GET['character']);
	$id = mysql_real_escape_string($_GET['drop']);
	$type = mysql_real_escape_string($_GET['type']);
	$user = mysql_real_escape_string($_GET['user']);
	if ($user != $current_user) { echo "Error! User Mismatch!"; exit; }
	if ($characterid != $current_character_id) { echo "Error! Character Mismatch!"; exit; }
	if ($type == 'armor') {
	$query = "UPDATE armors SET characterid='0',inRoom='$current_room' WHERE id='$id'";
	}
	if ($type == 'weapon') {
	$query = "UPDATE weapons SET characterid='0',inRoom='$current_room' WHERE id='$id'";
	}
	if ($type == 'tome') {
	$query = "UPDATE tomes SET characterid='0',inRoom='$current_room' WHERE id='$id'";
	}
	mysql_query($query) or die(mysql_error());
	end_turn($characterid);
	header("Location: index.php");
mysql_close();
	exit;
}
?>
<?php include('footer.php'); ?>