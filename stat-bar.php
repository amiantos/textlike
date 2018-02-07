<?php if ($current_character_id) { ?>

<div class="other-side-box">
<div style="float:left;">
<img src="logo.png" class="logo"/><br/>
<center class="online"><?php active_characters();?></center>
</div>
<div style="display:block;">
<?php
echo "<strong>H</strong>: ".$current_health."";
if ($bleeding > 0) {echo " (<strong style='color:red;'>".$bleeding."</strong>) &middot; "; } else { echo " &middot; "; }
echo "<strong>W</strong>: ".$current_weight."&#92;".$total_weight." &middot; ";
if ($available_points > 0) { echo "<strong style='color:red;'><a href='character.php'>Level up!</a></strong><br/>"; } else {
	$total_difference = $next_level_exp - $last_level;
	$current_difference = $next_level_exp - $experience;
	$amount_to_go = $total_difference - $current_difference;
	echo "<!--Next level:".$next_level_exp." Experience:".$experience." Difference:".$current_difference." Last Level:".$last_level."-->";
	echo "<!--Total Diff:".$total_difference." Current diff:".$current_difference."-->";
	$exp_percent = 100-(round(($current_difference / $total_difference)*100));
	if ($exp_percent == 100) { $exp_percent = 99; }
	echo "<strong>L</strong>: ".$char_level." (<span title='".$amount_to_go."&#92;".$total_difference."'>".$exp_percent."%</span>)<br/>"; 
 }
echo "<strong>W</strong>: "; display_equipped_weapon($current_character_id,$current_character_id,$user); echo "<br/>";
echo "<strong>A</strong>: "; display_equipped_armor($current_character_id,$current_character_id,$user);
?>
</div>
</div>
<?php if ($dead < 1) { ?>
<div class="other-side-box">
<?php 
$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$current_page = $parts[count($parts) - 1];
?>
<?php
if ($current_page != "index.php" && $dead < 1) { echo "<a href='index.php'>Home</a> &middot; "; }
if ($current_page != "inventory.php" && $dead < 1) { echo "<a href='inventory.php'>Inventory</a> "; }
if ($current_page == "index.php" && $dead < 1) { echo "&middot; "; }
if ($current_page != "character.php" && $dead < 1) { echo "<a href='character.php'>Character</a> "; }
if ($attacking > 0 && $dead < 1) {
echo "<div style='float:right;'>";
	if ($equipped_weapon_id > 0) {
		echo "<a href='processing.php?attack=1'>Attack</a>";
		} else { echo "<a href='processing.php?attack=1'>No Weapon</a>"; }
		if ($equipped_armor_id < 1) {echo " &middot; No Armor"; }
	echo "</div>";
} else {
	if ($current_room_level <= 25){
		$floor = 25 - ($current_room_level - 1);
	} else if ($current_room_level >= 26) {
		$floor = $current_room_level - 25;
		$floor = "B".$floor;
	}
	echo "<div style='float:right;'>";
	echo "Floor ".$floor;
	echo "</div>";
}
?>
</div>
<?php } ?>


<?php } ?>