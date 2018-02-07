<?php
include 'menu.php';
mysql_close();
?>

<div class="other-side-box" style="margin-bottom:20px;">
<table cellspacing=0 cellpadding=0 style="width:100%;">
<tr>
<td align='center' style="border-right:1px solid black;">
<a href="http://facebook.com/textlikegame">Facebook</a>
</td>
<td align='center' style="border-right:1px solid black;padding-left:8px;">
<a href="http://www.reddit.com/r/textlike">Reddit</a>
</td>
<td align='center' style="border-right:1px solid black;padding-left:8px;">
<a href="https://twitter.com/textlike">Twitter</a>
</td>
<td align='center' style="padding-left:8px;">
<small>&copy; 2014 <a href="http://amiantos.net">Brad Root</a></small>
</td>
</tr>
</table>
</div>

<script type="text/javascript">
	var a=document.getElementsByTagName("a");
for(var i=0;i<a.length;i++) {
    if(!a[i].onclick && a[i].getAttribute("target") != "_blank") {
        a[i].onclick=function() {
                window.location=this.getAttribute("href");
                return false; 
        }
    }
}
	</script>
	<script type="text/javascript" src="retina.js"></script>
</body>
</html>
