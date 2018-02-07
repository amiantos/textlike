<!-- MENU  HERE -->
<?php if($_SESSION['id']) { ?>
<div class="other-side-box" id="menubar">
<table cellspacing=0 cellpadding=0 style="width:100%;">
<tr>
<td align='center' style="border-right:1px solid black;">
<a href="index.php">Play</a>
</td>
<td align='center' style="border-right:1px solid black;padding-left:8px;">
<a href="scores.php">High Scores</a>
</td>
<td align='center' style="border-right:1px solid black;padding-left:8px;">
<a href="manual.php">Manual</a>
</td>
<td align='center' style="border-right:1px solid black;padding-left:8px;">
<a href="changelog.php">Changes</a>
</td>
<td align='center' style="padding-left:8px;">
<a href="?logoff">Log off</a>
</td>
</tr>
</table>
</div>
<?php } ?>