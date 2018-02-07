<?php include('header.php'); ?>
<?php include('headerhtml.php'); ?>

<?php if($_SESSION['id']) { $user_id = $_SESSION['id']; ?>

<!-- Character Info -->
<?php if (!$current_character_id) { include 'menu.php';?>
	<div class="other-side-box">
		<?php list_or_create_characters($user); ?>
	</div>
	
	<div class="other-side-box" style='margin-top:15px;'>
<center><H3>Your Previous Games</h3></center>
<?php
$query = "SELECT * FROM highscores WHERE user_id='$user_id' ORDER BY id DESC";
$result = mysql_query($query) or die(mysql_error());
echo "<P><ul>";
while($row = mysql_fetch_array($result)) { 
	echo "<li>";
	echo $row['character_name'];
	echo " &middot; Score ".$row['total_score'];
	echo " &middot; Level ".$row['level'];
	echo " &middot; Floor ".$row['floor'];
}
echo "</ol></P>";
?>
</div>

<?php } ?>
<!-- Menu -->
<?php if ($current_character_id) { ?>

<div class="other-side-box" id="statbar">
	
	<table cellspacing='0' cellpadding='0' style="width:100%;">
		<tr>
			<td rowspan='3' style='border-right:1px solid #808080;width:60px;'>
				<center style='margin-left:5px;margin-top:5px;'>
				<img src="logo.png" class="logo"/>
				<div class="online"><?php active_characters();?></div>
				</center>
			</td>
			<td>
				<table cellspacing=0 border=0 width='100%'>
					<tr>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;border-right:1px solid #808080;'>
							<?php 
								if (strlen($current_character) > 10) { echo "<small>"; }
								if (strlen($current_character) > 13) { echo "<small>"; }
								echo "<a href='processing.php?clear=".$user."'?>".$current_character."</a>";
							?>
						</td>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;border-right:1px solid #808080;'>
							<small style='font-variant:small-caps;'>Weight:</small>
							<?php
								echo $current_weight."&#92;".$total_weight;
							?>
							</td>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;'>
							<small style='font-variant:small-caps;'>Health:</small> 
							<?php
								echo $current_health;
								if ($bleeding > 0) {echo " (<strong style='color:red;'>".$bleeding."</strong>)"; }
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table cellspacing=0 border=0 width='100%'>
					<tr>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;border-right:1px solid #808080;'>
							<small style='font-variant:small-caps;'>Score:</small> <?php echo $score; ?>
						</td>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;border-right:1px solid #808080;'>
							<small style='font-variant:small-caps;'>Level:</small> <?php
							if ($available_points > 0) { echo "<span style='color:red;font-weight:bold;'>"; }
							echo $char_level;
							if ($available_points > 0) { echo "</span>"; }
							?></td>
						<td align=center style='padding:2px;border-bottom:1px solid #808080;'>
							<small style='font-variant:small-caps;'>Next:</small> 
							<?php
							$total_difference = $next_level_exp - $last_level;
							$current_difference = $next_level_exp - $experience;
							echo "<!--Next level:".$next_level_exp." Experience:".$experience." Difference:".$current_difference." Last Level:".$last_level."-->";
							echo "<!--Total Diff:".$total_difference." Current diff:".$current_difference."-->";
							$amount_to_go = $total_difference - $current_difference;
							$exp_percent = 100-(round(($current_difference / $total_difference)*100));
							if ($exp_percent == 100) { $exp_percent = 99; }
							echo "<span title='".$amount_to_go."&#92;".$total_difference."'>".$exp_percent."%</span>";
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<?php character_attributes($current_character_id,$current_character,$user); ?>
			</td>
		</tr>
	</table>

</div>

<?php if ($dead < 1) { ?>
<div class="other-side-box" id="actionbar">
<?php
			display_inventory_items_bar($current_character_id,$current_character_id,$user);
			if ($attacking > 0 && $dead < 1) {
				echo "<div style='float:right;' class='menuitem'>";
				echo "<a href='processing.php?defend=1'>Defend</a> &middot; ";
				if ($equipped_tome_id > 0) {
					echo "<a href='processing.php?tome=1' style='color:red;'>Tome</a> &middot; ";
				}
				if ($equipped_weapon_id > 0) {
					echo "<a href='processing.php?attack=1' style='color:red;'>Attack</a>";
				} else { echo "<a href='processing.php?attack=1'>Punch</a>"; }
				if ($equipped_armor_id < 1) {echo " &middot; No Armor"; }
				echo "</div>";
			} else {
				if ($current_room_level <= 25){
					$floor = 25 - ($current_room_level - 1);
				} else if ($current_room_level >= 26) {
						$floor = $current_room_level - 25;
						$floor = "B".$floor;
					}
				echo "<div style='float:right;' class='menuitem'>";
				echo "Floor ".$floor;
				echo "</div>";
			}
?>
</div>
<?php } ?>


<?php } ?>


<!-- Main Game Begins -->
<?php if ($dead) { ?>

	<div class="other-side-box"><h1>You Died</h1>
	<h2>Final Score: <?php echo $score; ?></h2>
	<center><P><a href="processing.php?subdel=1">Submit Score and Delete Character</a></P></center>
	</div>

<?php } else if ($encumbered > 0) { ?>

	<div class="other-side-box"><center><h2>You're Encumbered</h2></center>
	<P class="room-desc">You've become so weighed down with stuff you're no longer able to move. It's probably best if you lightened your load before you progress.</P>
	</div>

<?php } else if ($attacking > 0) { ?>

	<!-- ALL ATTACKING STUFF BELOW HERE -->
	
	<div class="other-side-box" style='margin-bottom:10px;'>
	<!-- How about Mob Info? -->
		<table cellspacing=0 border=0 width=100%>
			<tr>
				<td align=center style='border-bottom:1px solid #808080;padding:2px;'>
					<?php if (strlen(get_victim_name(get_victim($current_character_id))) > 20) { echo "<small>"; }
						echo ucwords(get_victim_name(get_victim($current_character_id))); ?>
				</td>
				<td align=center style='border-bottom:1px solid #808080;padding:2px;border-left:1px solid #808080;'>
					<small style='font-variant:small-caps;'>Level:</small> <?php echo get_victim_level(get_victim($current_character_id)); ?>
				</td>
				<td align=center style='border-bottom:1px solid #808080;padding:2px;border-left:1px solid #808080;'>
					<small style='font-variant:small-caps;'>Health:</small> <?php echo get_victim_health(get_victim($current_character_id)); ?> 
					(<?php $ebleed = is_victim_bleeding(get_victim($current_character_id));
						if ($ebleed > 0) { echo "<span style='font-weight:bold;color:red;'>";}
							echo $ebleed;
							if ($ebleed > 0) { echo "</span>";}?>)
				</td>
			</tr>
			<tr>
				<td colspan=3 align=center>
					<table cellspacing=0 border=0 width='100%'>
						<tr>
							<td align=center style='padding:2px;'>
								<small style='font-variant:small-caps;'>Resist:</small> <?php echo get_victim_resistance(get_victim($current_character_id)); ?>
							</td>
							<td align=center style='padding:2px;border-left:1px solid #808080;'>
								<small style='font-variant:small-caps;'>Weak:</small> <?php echo get_victim_weakness(get_victim($current_character_id)); ?>
							</td>
							<td align=center style='padding:2px;border-left:1px solid #808080;'>
								<small style='font-variant:small-caps;'>Str:</small> <?php echo mob_attribute(get_victim($current_character_id),1); ?>
							</td>
							<td align=center style='padding:2px;border-left:1px solid #808080;'>
								<small style='font-variant:small-caps;'>Sta:</small> <?php echo mob_attribute(get_victim($current_character_id),2); ?>
							</td>
							<td align=center style='padding:2px;border-left:1px solid #808080;'>
								<small style='font-variant:small-caps;'>Dex:</small> <?php echo mob_attribute(get_victim($current_character_id),3); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</div>
	
	<div class="other-side-box" id="attacking">		
		<?php
			get_battlelog($attacking,$current_character_id,0,$last_turn);
		?>
	</div>
	<!-- ALL ATTACKING STUFF ABOVE HERE-->

<?php } else { ?>

	<!-- ALL ROOM STUFF BELOW HERE -->
	<?php if ($current_character_id) { ?>
		<div class="other-side-box" id="mainroom">
	<?php }

		/* Load Story Element */
		if ($current_room > 0) {
			/* $rand_story = rand(0,2);
			if ($rand_story == 0) {
			echo "<div class='story-element'>";
			echo load_story_element($current_room_level);
			echo "</div>";
			} */
		}
		/* Is Character In A Room? */
		if ($current_character && $current_room == 0) {
			/* Character is not in a room: create Initial Room */
			create_initial_room($current_character_id);
			/* Over-ride default current_room var to new room so it'll load */
			$current_room = character_room($current_character_id);

			/* Load a Introductory Statement */
			echo "<P class='room-desc'>";
			echo load_intro_statement();
			echo "</P>";

			/* Generate and Place Introductory Chest */
			generate_chest($current_room,1,$current_character_id);

			/* Set "Don't Spawn Monsters Here" Flag */
			$query = "UPDATE rooms SET mobsGenned='1' WHERE id='$current_room'";
			mysql_query($query) or die(mysql_error());
		}

		/* Load Room */
		if ($current_room > 0) {
			echo "<P class='room-desc'>";
			if (check_room_description($current_room) == 1) {
				/* Load Room Description */
				echo get_room_description($current_room);
			} else if (check_room_description($current_room) == 0) {
					/* If None-exists: Create */
					create_room_description($current_room);
					/* Then Load Description */
					echo get_room_description($current_room);
				}
			echo "</p>";
		}

		/* Load Enemies */
		if ($current_room) {
			/* Generate Mob! */
			if (mobs_genned($current_room) != 1) {
				if ($char_level < 10) {
					$num_gen = rand(1,2);
				} else if ($char_level < 25) {
						$num_gen = rand(2,3);
					} else {
					$num_gen = rand(2,4);
				}
				if (rand(1,400) == 5) { $num_gen = 0; }
				while ($num_gen > 0){
					place_mob_in_room(generate_mob($current_character_id,0),$current_room);
					$num_gen = $num_gen - 1;
				}
				$query = "UPDATE rooms SET mobsGenned='1' WHERE id='$current_room'";
				mysql_query($query) or die(mysql_error());
				$mobs_in_room = mobs_in_room($current_room);
			}

			/* Load Mobs */
			echo "<P class='room-desc'>";
			load_mobs($current_room,$current_character_id,$user);
			echo "</P>";
		}

		/* Load Chests */
		if ($current_room) {
			load_chests($current_room,$current_character_id,$user);
		}

		/* Load Weapons, Armor On Floor */
		if ($current_room) {
			echo "<P class='room-desc'>";
			load_weapons($current_room,$current_character_id,$user);
			echo "</P>";
			echo "<P class='room-desc'>";
			load_armor($current_room,$current_character_id,$user);
			echo "</P>";
			echo "<P class='room-desc'>";
			load_tomes($current_room,$current_character_id,$user);
			echo "</P>";
			echo "<P class='room-desc'>";
			load_items($current_room,$current_character_id,$user);
			echo "</P>";
		}

		/* Load Room Exits */
		if ($current_room) {
			if ($mobs_in_room > 0) {
				echo "<P class='room-desc'>The door you came in locked behind you, maybe killing the enemies here will unlock it and reveal others?</P>";
			} else {
				echo "<P class='room-desc'>";
				find_exits($current_room,$current_character_id,$user);
				echo "</P>";
			}
		}


		if ($current_character_id) { ?>
	</div>
<?php } ?>

<!-- ALL ROOM STUFF ABOVE HERE -->

<?php } ?>

<?php
	if ($current_character_id && $dead != 1) { ?>
<div class="other-side-box" id="weaponbar">

<table cellpadding=0 border=0 cellspacing=0 style="margin:0px;width:100%;" class="inventory">
<?php
		display_inventory_weapons($current_character_id,$current_character_id,$user);
		display_inventory_armors($current_character_id,$current_character_id,$user);
		display_inventory_tomes($current_character_id,$current_character_id,$user);
?>
</table>

</div>


<?php } ?>

<!--- No one is logged in... --->
<?php } else { ?>
<br/>
<div class="other-side-box" style="padding-bottom:15px!important;"><center><img src="logo.png" class="logo"/></center>

 <P class="room-desc">Textlike is a <a href="http://en.wikipedia.org/wiki/Roguelike">roguelike</a> you can play in any web browser. I created it due to my love of games like <em>Diablo</em>, <em>The Binding of Isaac</em>, and classic text-based adventure games.</P>
 <P class="room-desc">Try it out and let me know what you think!</P>
</div>
<br/>
<div class="other-side-box" style="padding-bottom:10px!important;">
<center><H3>Login</h3>
<form class="cf" action="" method="post">
<?php
	if($_SESSION['msg']['login-err'])
	{
		echo '<h3>'.$_SESSION['msg']['login-err'].'</h3>';
		unset($_SESSION['msg']['login-err']);
	}
?>
<label class="grey" for="username">User:</label>
<input class="field" type="text" name="username" autocapitalize="off" id="username" value="" size="14" />
<label class="grey" for="password">Pass:</label>
<input class="field" type="password" name="password" autocapitalize="off" id="password" size="14" />
<input name="rememberMe" id="rememberMe" type="hidden" checked="checked" value="1" />
<input type="submit" name="submit" value="Login" class="bt_login" />
</form>
</div>
		<br/>
<!-- Register Form -->
<div class="other-side-box" style="padding-bottom:10px!important;">
<center>
<H3>Register</h3>
<?php
	if($_SESSION['msg']['reg-err'])
	{
		echo '<h3>'.$_SESSION['msg']['reg-err'].'</h3>';
		unset($_SESSION['msg']['reg-err']);
	}
	if($_SESSION['msg']['reg-success'])
	{
		echo '<h3>'.$_SESSION['msg']['reg-success'].'</h3>';
		unset($_SESSION['msg']['reg-success']);
	}
?>
<form action="" method="post">
<label class="grey" for="regusername">User:</label>
<input class="field" type="text" name="regusername" autocapitalize="off" id="regusername" value="" size="14" />
<label class="grey" for="regpassword">Pass:</label>
<input class="field" type="password" name="regpassword" autocapitalize="off" id="regpassword" size="14" />
<input type="submit" name="submit" value="Register" class="bt_register" />
</form>
<small><small>* <i>Passwords are hashed and non-recoverable.</i></small></small>
</div>

<br/>

<div class="other-side-box">
<center><H3>Top 5 High Scores</h3></center>
 <?php
	/* Grab Top 25 High Scores */
	$query = "SELECT user_name, MAX(total_score) FROM highscores GROUP BY user_name ORDER BY MAX(total_score) DESC LIMIT 5";
	$result = mysql_query($query) or die(mysql_error());
	echo "<ol>";
	while($row = mysql_fetch_array($result)) {
		echo "<li>";
		echo $row['MAX(total_score)'];
		echo " - ".$row['user_name'];
	}
	echo "</ol>";
?>
</div>
<br/>
<?php } ?>

<?php include('footer.php'); ?>