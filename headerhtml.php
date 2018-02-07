<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" itemscope itemtype="http://schema.org/CreativeWork">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Textlike v0.8.1</title>
	<?php
	if ($detect->isTablet()) {
    echo "<meta name='viewport' content='width=680'>";
    } else if ($detect->isMobile()) {
   echo "<meta name='viewport' content='width=580'>";
        }
    ?>
    <link rel='stylesheet' href='desktop.css' />
    
    <?php if (!$detect->isMobile() || $detect->isTablet()) { ?>
    
    <style>
    BODY {
	     font-size:15px !important;
	     width:440px !important;
    }
     .other-side-box {
	    padding:8px !important;
    }
    .logo {
	    width:40px !important;
    }
	</style>
    	
    <?php } ?>
    
	
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
	<link rel="shortcut icon" href="favicon.ico" />
	<link href='big-icon.png' rel='icon' type='image/png'>
	<meta content='yes' name='apple-mobile-web-app-capable'>
	<meta content='Textlike' name='apple-mobile-web-app-title'>
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
<meta content='black' name='apple-mobile-web-app-status-bar-style'>
<link href='apple-touch-icon.png' rel='apple-touch-icon'>
<link href='touch-icon-ipad.png' rel='apple-touch-icon' sizes='76x76'>
<link href='big-icon-iphone-retina.png' rel='apple-touch-icon' sizes='120x120'>
<link href='big-icon-retina.png' rel='apple-touch-icon' sizes='152x152'>

	<meta itemprop="name" content="Textlike">
<meta itemprop="description" content="Textlike is a text-based randomly generated roguelike you can play in your browser. Can you get the highest score...?">
	<META NAME="Description" CONTENT="Textlike is a text-based randomly generated roguelike you can play in your browser. Can you get the highest score...?">
	
<link rel="stylesheet" type="text/css" href="style/addtohomescreen.css">
<script src="src/addtohomescreen.js"></script>
</head>

<body>
<?php include_once("analyticstracking.php") ?>

<?php if($_SESSION['id']) { ?>

<script>
addToHomescreen({
   skipFirstVisit: true,
   maxDisplayCount: 1
});
</script>

<?php 
/* set up some variables we'll want to have */
$user = $_SESSION['usr'];
$current_character_id = selected_character_id($user);
$current_character = htmlspecialchars(selected_character($current_character_id));
$available_points = available_points($current_character_id);
$current_room = character_room($current_character_id);
$current_room_level = room_level($current_room);
$num_of_turns = num_of_turns($current_character_id);
$last_turn = $num_of_turns-1;
$attacking = get_victim($current_character_id);
$dead = death($current_character_id);
$total_health = total_health($current_character_id);
$current_health = current_health($current_character_id);
$bleeding = is_bleeding($current_character_id);
$experience = experience($current_character_id);
$next_level_exp = next_level_exp($current_character_id);
$char_level = current_char_level($current_character_id);
$mobs_in_room = mobs_in_room($current_room);
$total_weight = get_total_weight($current_character_id);
$current_weight = get_current_weight($current_character_id);
$encumbered = encumbered($current_character_id);
$last_level = last_level($current_character_id);
$equipped_weapon_id = return_equipped_weapon_id($current_character_id);
$equipped_tome_id = return_equipped_tome_id($current_character_id);
$bandages_total = count_bandages($current_character_id);
$apples_total = count_apples($current_character_id);
$equipped_armor_id = return_equipped_armor_id($current_character_id);
$armor_dur = armor_durability($equipped_armor_id);
if ($num_of_turns > 0) {
$total_score = $experience;
}
if ($current_room_level == '') { $current_room_level = 1; }

echo "<!--";
echo "Current Character ID: ".$current_character_id;

echo "Current Character: ".$current_character;

echo "Current Room: ".$current_room;

echo "Armor Dur: ".$armor_dur." Armor ID: ".$equipped_armor_id;
echo "Encumbered: ".$encumbered;
echo "Mobs: ".$mobs_in_room;
echo "Current Room: ".$current_room;
if ($num_of_turns != 0) { $score = ($experience / ($num_of_turns/2))*100; } else {$score = 0;}
$score = round($score);
echo "Possible Score: ".$score." Turns: ".$num_of_turns." Weapon ID:".$equipped_weapon_id;
echo "Current Exp: ".$experience." and Last Level: ".$last_level." Next Level:".$next_level_exp;
if ($current_character_id>0) { echo  "Luck Check: ".luck_check($current_character_id)." Attacking: ".$attacking; }
echo "Bandages: ".$bandages_total;
echo "apples: ".$apples_total;
echo "Current Floor: ".$current_room_level;
echo "-->";
?>

<?php } ?>