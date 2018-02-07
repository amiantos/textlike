<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

function load_intro_statement() {
$what = rand(0,3);
if ($what == 0) { $intro = "You woke up. For a second you forgot why you were here, or where here even was, but then you remembered and a calmness washed over you.";}
if ($what == 1) { $intro = "Welcome to the rest of your life. Maybe if you reach the bottom, you'll get to survive?";}
if ($what == 2) { $intro = "Where are you? How did you get here? Wait... who are you? Maybe the better question is, what did you do to end up here?";}
if ($what == 3) { $intro = "You sniff the air. There are hints of jasmine and bitter almond, or maybe that is just a memory you'd rather forget.";}
return $intro;
}

function create_room_description($room_id) {
/* Load Room Data */
$query = "SELECT * FROM rooms WHERE (id='$room_id')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	$main_room_id = $row['id'];
	$main_room_characterid = $row['characterid'];
	$north = $row['north'];
	$south = $row['south'];
	$east = $row['east'];
	$west = $row['west'];
	$up = $row['up'];
	$down = $row['down'];
	$main_room_level = $row['level'];
}

/* Build Room Description */

/* Introductory Statement On Room */
$what = rand(1, 3);
if ($what == 1) { $intro = "The air here is hot, and you can feel it in your lungs; whatever it is floating in the air, it is inside you. ";}
if ($what == 2) { $intro = "You see your breath in the air before you even have a chance to shiver. You try rubbing the cold out of your arms, but it's already cut you to the bone. ";}
if ($what == 3) { $intro = "This room is filled with a thick cloud of swirling sand, being pushed by a wind you can't find the source of. You have to put a cloth over your mouth to breathe, and squint at your surroundings through pinched fingers. ";}

/* On The Floors */
$what = rand(0, 3);
if ($what == 0) { $floor = "The ground under your feet feels moist and sticky. You think that falling down here would turn into a messy ordeal. ";}
if ($what == 1) { $floor = "With every step you take, cockroaches crunch under your feet; countless others race away into the dark corners. ";}
if ($what == 2) { $floor = "If you stand in place too long, the sand all over the floor begins to swallow your feet. If you keep moving, you should be fine. ";}
if ($what == 3) { $floor = "The ground under your feet is slick with a clear substance. It glistens in what little light there is. It appears to be the trails of several giant snails, but how they came into, and slipped out of, the room isn't apparent to you.";}

/* On The Walls */
$what = rand(0, 3);
if ($what == 0) { $wall = "Adorning the walls of this room are enormous engravings, depicting the fallen heroes of civilizations long forgotten. ";}
if ($what == 1) { $wall = "Hanging on one of the walls of this room is large painting of a horse. The horse is screaming. ";}
if ($what == 2) { $wall = "The walls of this room are covered in a beautiful mosaic. The tiles of turquoise, amethyst, and quartz form an elaborate scene of a bustling market place in the city, which stands in sharp contrast to the desolation around you. When you look closer, however, you see that the wears on display in the market are actually desiccated pieces of dismembered corpses.  ";}
if ($what == 3) { $wall = "Before you, large blood red tapestries hang from the walls, adorned with a strange crest you've never seen before. ";}

/* Over Your Head */
$what = rand(0, 3);
if ($what == 0) { $head = "Suspended over your head by chains, the bodies of the long dead and forgotten sway, as if jostled by someone else who was here just before you. ";}
if ($what == 1) { $head = "At first you think the ceiling is pulsating, but you realize that it is home to hundreds of bats. You decide it's best not to disturb them. ";}
if ($what == 2) { $head = "From above you can hear the sound of something slowly chewing on something else, but the ceiling is too high and all you can see is darkness. Whatever it making that sound, you hope it's friendly, or at least totally distracted already. ";}
if ($what == 3) { $head = "The sound of chains clinking together fills the chamber as you move through it, pushing away the variety of hooks hanging down from above. ";}

/* Special Mythical Features */
$what = rand(0, 3);
if ($what == 0) { $special = "Coming from a hole in the base of one of the walls, a small stream cuts its way through the center of this room. The pebbles at the bottom of the stream have been worn smooth by decades of tumbling water. ";}
if ($what == 1) { $special = "Though you can't fathom why, a small crack in the ceiling exposes a little of what must be sunlight. You can feel the warmth of it on your face; taking a moment to enjoy it, you remember something you had forgotten long ago. ";}
if ($what == 2) { $special = "You only saw it for a moment when you entered, but you swear there was a faery hovering in the middle of the room, only to vanish into a crack in the wall when it noticed you come in. ";}
if ($what == 3) { $special = "A stream of water falls from a hole in the ceiling and into a hole in the floor. Both holes are too precise to be natural. Where is this water going? Where is it from? ";}

/* Describe Exits */

$what = rand(0, 4);
if ($what == 0) { $room_desc = mysql_real_escape_string($intro); }
if ($what == 1) { $room_desc = mysql_real_escape_string($floor);}
if ($what == 2) { $room_desc = mysql_real_escape_string($wall);}
if ($what == 3) { $room_desc = mysql_real_escape_string($head);}
if ($what == 4) { $room_desc = mysql_real_escape_string($special);}

/* Write Room Description */

$query = "UPDATE rooms SET roomDesc='$room_desc' WHERE id='$room_id'";
mysql_query($query) or die(mysql_error());	

}

?>