<?php include('header.php'); ?>
<?php include('headerhtml.php');
	include 'menu.php';
?>

<?php if($_SESSION['id']) { ?>

<div class="other-side-box"><h2>High Scores</h2>
<?php
/* Grab Top 25 High Scores */
$query = "SELECT user_name, MAX(total_score) FROM highscores GROUP BY user_name ORDER BY MAX(total_score) DESC";
$result = mysql_query($query) or die(mysql_error());
echo "<P><ol>";
while($row = mysql_fetch_array($result)) { 
	echo "<li>";
	echo $row['MAX(total_score)'];
	echo " - ".$row['user_name'];
}
echo "</ol></P>";
?>
</div>
<br/>
<div class="other-side-box"><h2>Recent Deaths</h2>
<?php
$query = "SELECT * FROM highscores ORDER BY id DESC";
$result = mysql_query($query) or die(mysql_error());
echo "<P><ul>";
while($row = mysql_fetch_array($result)) { 
	echo "<li>";
	echo $row['user_name'];
	echo " &middot; ".$row['total_score'];
	echo " &middot; Level ".$row['level'];
	echo " &middot; Floor ".$row['floor'];
}
echo "</ol></P>";
?>
</div>

<? } else { ?>

<?php } ?>

<?php include('footer.php'); ?>