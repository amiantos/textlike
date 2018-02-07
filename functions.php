<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

function checkEmail($str) {
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}

function humanTiming ($time)
{
    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second');

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}

/* Game Functions Here */

/* Create Initial Room */
function create_initial_room($current_character_id) {
/* Create Room */
$query = "INSERT INTO rooms (characterid,x,y,created_for) VALUES ('$current_character_id','0','0','$current_character_id')";
mysql_query($query) or die(mysql_error());
/* Place Character In Room */
$query = "SELECT * FROM rooms WHERE (characterid='$current_character_id') AND (x='0') AND (y='0')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$roomid = $row['id'];
}
$query = "UPDATE characters SET inRoom='$roomid' WHERE id='$current_character_id'";
mysql_query($query) or die(mysql_error());
create_offshoot_rooms($roomid);	
}

/* Generate Branching Rooms */
function create_offshoot_rooms($room_id) {

/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$main_room_id = $row['id'];
	$main_room_characterid = $row['characterid'];
	$main_room_x = $row['x'];
	$main_room_y = $row['y'];
	$main_room_level = $row['level'];
}
a:
/* Guarantee Generation of at least 1 room
if (number_of_rooms($main_room_id) < 4) {
$adirection = rand(0,3);
if ($adirection == 0) { $override = 'n'; }
if ($adirection == 1) { $override = 's'; }
if ($adirection == 2) { $override = 'e'; }
if ($adirection == 3) { $override = 'w'; }
}*/

// Pick random direction to start room generation at
$adirection = rand(0,3);
if ($adirection == 0) { goto n; }
if ($adirection == 1) { goto s; }
if ($adirection == 2) { goto e; }
if ($adirection == 3) { goto w; }

n:
/* See if Northern room exists */
$check = $main_room_x + 1;
$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') AND (x='$check') AND (y='$main_room_y')";
$result = mysql_query($query) or die(mysql_error());
$numrows=mysql_num_rows($result);

/* If Room Exists, Add To Main Room Data */
if ($numrows == 1) {
while($row = mysql_fetch_array($result)) {
	$new_room_id = $row['id'];
}
$query = "UPDATE rooms SET north='$new_room_id' WHERE id='$main_room_id'";
mysql_query($query) or die(mysql_error());	
/* And add to that room's data just in case */
$query = "UPDATE rooms SET south='$main_room_id' WHERE id='$new_room_id'";
mysql_query($query) or die(mysql_error());	
/* change override
	if ($override == 'n') {
		$adirection = rand(0,2);
		if ($adirection == 0) { $override = 'w'; }
		if ($adirection == 1) { $override = 's'; }
		if ($adirection == 2) { $override = 'e'; }
	}*/
}

/* If Room Does Not Exists, Do Something Else */
if ($numrows == 0) {
	$what = rand(0, 4);
	if ($override == 'n') { $what = 1; }
	if ($what == 1 && number_of_rooms($main_room_id) < 4) {
	$direction = 'north';
	$new_room_id = create_new_room($check,$main_room_y,$main_room_level,$main_room_characterid,$direction,$main_room_id);
	$query = "UPDATE rooms SET north='$new_room_id' WHERE id='$main_room_id'";
	mysql_query($query) or die(mysql_error());
	if (number_of_rooms($main_room_id) <= 5) { $genned = 1; }
	}
}
s:
/* See if Southern room exists */
$check = $main_room_x - 1;
$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') AND (x='$check') AND (y='$main_room_y')";
$result = mysql_query($query) or die(mysql_error());
$numrows=mysql_num_rows($result);

/* If Room Exists, Add To Main Room Data */
if ($numrows == 1) {
while($row = mysql_fetch_array($result)) {
	$new_room_id = $row['id'];
}
$query = "UPDATE rooms SET south='$new_room_id' WHERE id='$main_room_id'";
mysql_query($query) or die(mysql_error());
/* And add to that room's data just in case */
$query = "UPDATE rooms SET north='$main_room_id' WHERE id='$new_room_id'";
mysql_query($query) or die(mysql_error());	
// change override
	/*if ($override == 's') {
		$adirection = rand(0,2);
		if ($adirection == 0) { $override = 'n'; }
		if ($adirection == 1) { $override = 'w'; }
		if ($adirection == 2) { $override = 'e'; }
	}*/
}

/* If Room Does Not Exists, Do Something Else */
if ($numrows == 0) {
	$what = rand(0, 4);
	if ($override == 's') { $what = 1; }
	if ($what == 1 && number_of_rooms($main_room_id) < 4) {
	$direction = 'south';
	$new_room_id = create_new_room($check,$main_room_y,$main_room_level,$main_room_characterid,$direction,$main_room_id);
	$query = "UPDATE rooms SET south='$new_room_id' WHERE id='$main_room_id'";
	mysql_query($query) or die(mysql_error());
	if (number_of_rooms($main_room_id) <= 5) { $genned = 1; }
	}
}
w:
/* See if Western room exists */
$check = $main_room_y - 1;
$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') AND (x='$main_room_x') AND (y='$check')";
$result = mysql_query($query) or die(mysql_error());
$numrows=mysql_num_rows($result);

/* If Room Exists, Add To Main Room Data */
if ($numrows == 1) {
while($row = mysql_fetch_array($result)) {
	$new_room_id = $row['id'];
}
$query = "UPDATE rooms SET west='$new_room_id' WHERE id='$main_room_id'";
mysql_query($query) or die(mysql_error());
/* And add to that room's data just in case */
$query = "UPDATE rooms SET east='$main_room_id' WHERE id='$new_room_id'";
mysql_query($query) or die(mysql_error());	
	// change override
	/*if ($override == 'w') {
		$adirection = rand(0,2);
		if ($adirection == 0) { $override = 'n'; }
		if ($adirection == 1) { $override = 's'; }
		if ($adirection == 2) { $override = 'e'; }
	}*/
}

/* If Room Does Not Exists, Do Something Else */
if ($numrows == 0) {
	$what = rand(0, 4);
	if ($override == 'w') { $what = 1; }
	if ($what == 1 && number_of_rooms($main_room_id) < 4) {
	$direction = 'west';
	$new_room_id = create_new_room($main_room_x,$check,$main_room_level,$main_room_characterid,$direction,$main_room_id);
	$query = "UPDATE rooms SET west='$new_room_id' WHERE id='$main_room_id'";
	mysql_query($query) or die(mysql_error());
	if (number_of_rooms($main_room_id) <= 5) { $genned = 1; }
	}
}
e:
/* See if Eastern room exists */
$check = $main_room_y + 1;
$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') AND (x='$main_room_x') AND (y='$check')";
$result = mysql_query($query) or die(mysql_error());
$numrows=mysql_num_rows($result);
/* If Room Exists, Add To Main Room Data */
if ($numrows == 1) {
while($row = mysql_fetch_array($result)) {
	$new_room_id = $row['id'];
}
$query = "UPDATE rooms SET east='$new_room_id' WHERE id='$main_room_id'";
mysql_query($query) or die(mysql_error());
/* And add to that room's data just in case */
$query = "UPDATE rooms SET west='$main_room_id' WHERE id='$new_room_id'";
mysql_query($query) or die(mysql_error());	
// change override
	/*if ($override == 'e') {
		$adirection = rand(0,2);
		if ($adirection == 0) { $override = 'n'; }
		if ($adirection == 1) { $override = 's'; }
		if ($adirection == 2) { $override = 'w'; }
	}*/
}
/* If Room Does Not Exists, Do Something Else */
if ($numrows == 0) {
	$what = rand(0, 4);
	if ($override == 'e') { $what = 1; }
	if ($what == 1 && number_of_rooms($main_room_id) < 4) {
	$direction = 'east';
	$new_room_id = create_new_room($main_room_x,$check,$main_room_level,$main_room_characterid,$direction,$main_room_id);
	$query = "UPDATE rooms SET east='$new_room_id' WHERE id='$main_room_id'";
	mysql_query($query) or die(mysql_error());
	if (number_of_rooms($main_room_id) <= 5) { $genned = 1; }
	}
}

if (!$genned && number_of_rooms($main_room_id) < 4) {
	goto a;
}

/* New Stairs Generator */
if (!$genned) {
	$stairs_exist = do_stairs_exist($main_room_id);
	if ($stairs_exist == 0) {
		$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') AND (roomsGenned='0')";
		$result = mysql_query($query) or die(mysql_error());
		$numrows=mysql_num_rows($result);
		/* If no unexplored rooms, create staircase here */
		if ($numrows == 0) {		
			$direction = 'down';
			$new_level = $main_room_level + 1;
			$new_room_id = create_new_room($main_room_x,$main_room_y,$new_level,$main_room_characterid,$direction,$main_room_id);
			$query = "UPDATE rooms SET down='$new_room_id' WHERE id='$main_room_id'";
			mysql_query($query) or die(mysql_error());
		} else {
		/* If there is an unexplored room, create one in that room */
			while($row = mysql_fetch_array($result)) {
			$empty_room_id = $row['id'];
			$empty_room_x = $row['x'];
			$empty_room_y = $row['y'];
			}
			$direction = 'down';
			$new_level = $main_room_level + 1;
			$new_room_id = create_new_room($empty_room_x,$empty_room_y,$new_level,$main_room_characterid,$direction,$empty_room_id);
			$query = "UPDATE rooms SET down='$new_room_id' WHERE id='$empty_room_id'";
			mysql_query($query) or die(mysql_error());
		}
		// Let's create a "boss mob", or at least let's see if we can, and put it in a random room.
			$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level')";
			$result = mysql_query($query) or die(mysql_error());
			$numrows=mysql_num_rows($result);
			$rand_number = rand(1,$numrows);
			if ($rand_number == 1) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1";
			} else if ($rand_number == 2) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 1";
			} else if ($rand_number == 3) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 2";
			} else if ($rand_number == 4) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 3";
			} else if ($rand_number == 5) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 4";
			} else if ($rand_number == 6) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 5";
			} else if ($rand_number == 7) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 6";
			} else if ($rand_number == 8) {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1 OFFSET 7";
			} else {
				$query = "SELECT * FROM rooms WHERE (characterid='$main_room_characterid') AND (level='$main_room_level') LIMIT 1";
			}
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_array($result)) {
			$random_room = $row['id'];
			}
			$boss_level = $main_room_level + 8;
			place_mob_in_room(generate_mob($main_room_characterid,$main_room_level),$random_room);
	}
}

/* At the End, Set Rooms Genned Flag */
$query = "UPDATE rooms SET roomsGenned='1' WHERE id='$main_room_id'";
mysql_query($query) or die(mysql_error());	

}

/* Create A Room */
function create_new_room($x,$y,$level,$characterid,$direction,$main_room_id) {
if ($direction == 'north') { $query = "INSERT INTO rooms (characterid,x,y,level,south,created_for) VALUES ('$characterid','$x','$y','$level','$main_room_id','$characterid')"; }
if ($direction == 'south') { $query = "INSERT INTO rooms (characterid,x,y,level,north,created_for) VALUES ('$characterid','$x','$y','$level','$main_room_id','$characterid')"; }
if ($direction == 'east') { $query = "INSERT INTO rooms (characterid,x,y,level,west,created_for) VALUES ('$characterid','$x','$y','$level','$main_room_id','$characterid')"; }
if ($direction == 'west') { $query = "INSERT INTO rooms (characterid,x,y,level,east,created_for) VALUES ('$characterid','$x','$y','$level','$main_room_id','$characterid')"; }
if ($direction == 'down') { $query = "INSERT INTO rooms (characterid,x,y,level,up,created_for) VALUES ('$characterid','$x','$y','$level','$main_room_id','$characterid')"; }
mysql_query($query) or die(mysql_error());
/* Grab New Room ID */
$query = "SELECT * FROM rooms WHERE (characterid='$characterid') AND (x='$x') AND (y='$y') AND (level='$level')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$roomid = $row['id'];
}
/* Add Random Chest To Room? */
$chest = rand(1,3);
if ($chest == 1) { generate_chest($roomid,0,$characterid); }
return $roomid;
}

/* Find Exits */
function find_exits($current_room,$current_character_id,$user) {
/* If Rooms Haven't Been Genned, Gen Rooms First */
$query = "SELECT * FROM rooms WHERE (id='$current_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$genned = $row['roomsGenned'];
}
if ($genned == '0') {
create_offshoot_rooms($current_room);
}

/* Find Available Exits */
$query = "SELECT * FROM rooms WHERE (id='$current_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$north_room = $row['north'];
	$south_room = $row['south'];
	$east_room = $row['east'];
	$west_room = $row['west'];
	$down_stairs = $row['down'];
	$up_stairs = $row['up'];
	$level = $row['level'];
}
if ($north_room > 0) { $numrows = $numrows + 1;}
if ($south_room > 0) { $numrows = $numrows + 1;}
if ($east_room > 0) { $numrows = $numrows + 1;}
if ($west_room > 0) { $numrows = $numrows + 1;}

if ($numrows == 1) {
	echo "There is a doorway leading to the ";
} else if ($numrows >= 2) {
	echo "There are doorways leading to the ";
}
echo "<!-- Num rows rooms :".$numrows."-->";
$numrows_total = $numrows;
/* Print Exits */
if ($north_room > 0) {
/* Check If Explored */
$query = "SELECT * FROM rooms WHERE (id='$north_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
if ($numrows == 1 && $numrows_total > 1) { echo " and ";}
if ($explored==1) { echo "<strike>"; }
echo "<a href='processing.php?move-char=".$north_room."&character=".$current_character_id."&user=".$user."'/>North</a>";
$numrows = $numrows - 1;
if ($explored==1) { echo "</strike>"; }
if ($numrows > 1) { echo ", ";}
}
if ($south_room > 0) {
$query = "SELECT * FROM rooms WHERE (id='$south_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
if ($numrows == 1 && $numrows_total > 1) { echo " and ";}
if ($explored==1) { echo "<strike>"; }
echo "<a href='processing.php?move-char=".$south_room."&character=".$current_character_id."&user=".$user."'/>South</a>";
$numrows = $numrows - 1;
if ($explored==1) { echo "</strike>"; }
if ($numrows > 1) { echo ", ";}
}
if ($east_room > 0) {
$query = "SELECT * FROM rooms WHERE (id='$east_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
if ($numrows == 1 && $numrows_total > 1) { echo " and ";}
if ($explored==1) { echo "<strike>"; }
echo "<a href='processing.php?move-char=".$east_room."&character=".$current_character_id."&user=".$user."'/>East</a>";
$numrows = $numrows - 1;
if ($explored==1) { echo "</strike>"; }
if ($numrows > 1) { echo ", ";}
}
if ($west_room > 0) {
$query = "SELECT * FROM rooms WHERE (id='$west_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
if ($numrows == 1 && $numrows_total > 1) { echo " and ";}
if ($explored==1) { echo "<strike>"; }
echo "<a href='processing.php?move-char=".$west_room."&character=".$current_character_id."&user=".$user."'/>West</a>";
$numrows = $numrows - 1;
if ($explored==1) { echo "</strike>"; }
if ($numrows > 1) { echo ", ";}
}


/* Check if Boss Mob Killed for Level */
	$query = "SELECT * FROM mobs WHERE (boss='$level') AND (corpse='0') AND (characterid='$current_character_id')";
	$result = mysql_query($query) or die(mysql_error());
	$bosses=mysql_num_rows($result);
	
	if ($bosses == 0) {

	if ($down_stairs > 0 || $up_stairs > 0) {
		echo ". In the center of the room, a staircase leading ";
	} else {
		echo ".<br/>";
	}	

if ($down_stairs > 0) {
	/* Check If Explored */
$query = "SELECT * FROM rooms WHERE (id='$down_stairs')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
		if ($explored != 0) { echo "<strike>"; }
		echo "<a href='processing.php?move-char=".$down_stairs."&character=".$current_character_id."&user=".$user."'/>downward</a>.";
		if ($explored != 0) { echo "</strike>";}
}

if ($up_stairs > 0) {
	/* Check If Explored */
$query = "SELECT * FROM rooms WHERE (id='$up_stairs')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$explored = $row['roomsGenned'];
}
if ($explored != 0) { echo "<strike>"; }
echo "<a href='processing.php?move-char=".$up_stairs."&character=".$current_character_id."&user=".$user."'/>upward</a>.";
if ($explored != 0) { echo "</strike>"; }
}

} else if ($down_stairs > 0 || $up_stairs > 0) {

	echo ". The staircase is locked until you defeat the boss on this level.<br/>";
} else { echo ". "; }

}

/* Get Number Of Rooms On Level */
function number_of_rooms($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$main_room_characterid = $row['characterid'];
	$main_room_level = $row['level'];
}
/* Count Rooms */
$query = "SELECT * FROM rooms WHERE (level='$main_room_level') AND (characterid='$main_room_characterid')";
$result = mysql_query($query) or die(mysql_error());
$number_of_rooms=mysql_num_rows($result);
return $number_of_rooms;
}

/* Get Level of Current Room */
function room_level($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$main_room_level = $row['level'];
}
return $main_room_level;
}

/* Do Stairs Exist On This Level? */
function do_stairs_exist($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$main_room_characterid = $row['characterid'];
	$main_room_level = $row['level'];
}
/* Find Stairs */
$query = "SELECT * FROM rooms WHERE (level='$main_room_level') AND (characterid='$main_room_characterid') AND (down!='0')";
$result = mysql_query($query) or die(mysql_error());
$number_of_rooms=mysql_num_rows($result);
return $number_of_rooms;
}

/* Check For Room Description */
function check_room_description($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	if ($row['roomDesc'] != '') { $roomDesc = 1; } else { $roomDesc = 0; }
}
return $roomDesc;
}

/* Output For Room Description */
function get_room_description($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$roomDesc = $row['roomDesc'];
}
return $roomDesc;
}

/* Count Mobs In Room */
function mobs_in_room($current_room) {
$query = "SELECT * FROM mobs WHERE (inRoom='$current_room') AND (corpse='0')";
$result = mysql_query($query) or die(mysql_error());
$number_of_mobs=mysql_num_rows($result);
return $number_of_mobs;
}

/* Active Characters */
function active_characters() {
/* Load Character Info */
$query = "SELECT * FROM characters WHERE DATE_SUB(NOW(), INTERVAL 10 MINUTE) <= lastModified";
$result = mysql_query($query) or die(mysql_error());
$active_chars=mysql_num_rows($result);
$num = $active_chars;
echo "Online: <span title='";
while($row = mysql_fetch_array($result)) {
echo $row['user'];
if ($num > 1) {echo ", ";}
$num = $num - 1;
}
echo "'>".$active_chars."</span>";
}

?>