<?php
/* End Turn */
function end_turn($character_id) {
/* Run auto heal */
auto_heal($character_id);
/* Check for Level Up */
/* If Player Is In Battle, Have Enemy Attack */
$attacking = get_victim($character_id);
if ($attacking) {
	mob_attack($attacking,$character_id);
}
/* Update Character Bleeding */
update_bleeding($character_id);
/* Update Enemy Bleeding */
update_bleeding_mob($character_id);
/* Check for Equipped Weapon/Armor Break */
are_equipped_items_broken($character_id);
/* Check for player death */
is_player_dead($character_id);
is_mob_dead($character_id);
/* Check for Player Over-Encumbrance */
	$total_weight = get_total_weight($character_id);
	$current_weight = get_current_weight($character_id);
	if ($current_weight > $total_weight) {
	/* Set Encumbrance Flag */
	$query = "UPDATE characters SET encumbered=1 WHERE id='$character_id'";
	mysql_query($query) or die(mysql_error());
	} else {
	/* Unset it */
	$query = "UPDATE characters SET encumbered=0 WHERE id='$character_id'";
	mysql_query($query) or die(mysql_error());
	}
/* Increment Character Turn Data */
$query = "UPDATE characters SET turns = turns + 1 WHERE id='$character_id'";
mysql_query($query) or die(mysql_error());
level_up_check($character_id);
}
?>