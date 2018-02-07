<?php

function get_room_history($current_room) {
$query = "SELECT * FROM roomhistory WHERE (room_id='$current_room')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) { 
	echo $row['history'];
	echo "<br/>";
}
}

function write_room_history($room_history,$room_id,$character_id) {
$query = "INSERT INTO roomhistory (room_id,character_id,history,created_for) VALUES ('$room_id','$character_id','$room_history','$character_id')";
mysql_query($query) or die(mysql_error());
}

/* Count Room History */
function count_history($current_room) {
$query = "SELECT * FROM roomhistory WHERE (room_id='$current_room')";
$result = mysql_query($query) or die(mysql_error());
$numrows=mysql_num_rows($result);
return $numrows;
}

?>