<?php

session_start();

//This database class should remain hidden as it contains magic.
include "ImagerollDB.php";
$roll = new Imageroll();
$roll->connect();

//If user is logged in, present logout buttons
if(isset($_SESSION['name'])&&(isset($_SESSION['code']))&&(isset($_SESSION['capture']))){
	if($roll->isActiveUser($_SESSION['name'],$_SESSION['code'],$_SESSION['capture'])){
		$logout = '<form name="logout" method="post" action="logout.php">';
		$logout .= '<input type="submit" name="logout" id="logout" value="Logout" align="ABSMIDDLE"></form>';
	}
	else{
		header("location:index.php?error=login");
	}
}
else{
	header("location:index.php?error=login");
}

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Imageroll!</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="imageroll.css" type="text/css" />
<script type="text/javascript" src="script/jquery.js"></script>
<script src="script/jquery.tabify.js" type="text/javascript"></script>
<script src="script/ajax.js" language="javascript"></script>

<script type="text/javascript">				
$(document).ready(function () {
	$('#menu').tabify();
});					
</script>

</head>
<body>
<div class="header2"><br />
<a href="index.php" target="_self"><img height="90%" width="50%" src="Img/rsz_logo.png" class="head"/></a><hr>
</div>
<div class="linkage">
<ul id="menu" class="menu">
			<li class="active"><a href="#welcome">Info</a></li>
			<li><a href="#image">Image Upload</a></li>
			<li><a href="#chat">Chat</a></li>
</ul>
		<div id="welcome" class="content">
			<p id="pp"><b>Welcome, <font color="#FFF"><? echo $_SESSION['name']; ?></font></b><br /><br />		
				<font color="#FFF">How does it work!?</font><br /></p>
				<ol><li>Link to an image.</li>
					<li>Pose a question.</li>
					<li>Image put in Queue.</li>
					<li>Image displayed.</li>
					<li>Discussion.</li>
					<li>Print out of feedback.</li>
					<li>Image is deleted.</li>
				</ol>
			
			<br />
			<p><? if(isset($logout)){ echo $logout;} ?></p>
		</div>
		<div id="image" class="content">
			<?
				//This div displays errors/user messages.
				echo '<div id="response" class="response"></div>';
				echo '<p id="pp">
				<u>Only JPG, PNG and GIF...</u><br /></p>
				Paste image link here:
				<form action="javascript:addPic()" class="link" id="link">
				<input type="text" id="urls" class="required" placeholder="           URL" size="15" /><br /><br />
				Pose your question:
				<input type="text" id="ques" class="required" placeholder="   Question/Caption" size="15" /><br>
				<br>
				<input type="hidden" id="username" class="username" value="'.$_SESSION['name'].'" />
				<input type="hidden" id="code" class="code" value="'.$_SESSION['code'].'" />
				<br /><br /><input type="submit" name="submit" id="submit" value="Submit" /><br /><br />
				</form>';
			?>
		</div>
		<!--Chat div-->
		<div id="chat" class="content">
		<p id="pp">There is a limit of 140 characters.</p>
		<iframe src="chat.php" style="overflow" scrolling="yes" width="190" height="280"></iframe>
		<form action="javascript:submitmsg()" class="chatwin" id="chatwin" name="chatwin" >
		<input type="text" id="msg" class="msg" name="msg" placeholder="Say something!" size="15" /><br>
		<input type="hidden" id="nam" class="username" value="<? echo $_SESSION['name']; ?>" />
		<input type="hidden" id="cod" class="code" value="<? echo $_SESSION['code']; ?>" />
		</form><br />
		</div>

</div>
</div>
<div class="imagebox">
<iframe frameborder="0" frameborder="no" src="imagebox.php" width="640" height="500"></iframe>
</div>
<div class="bottoms">
<hr>
<h2>Logged in</h2>
<? 
$uz = $roll->getActiveUsers();
$userss = '<b>Users:</b>';
foreach($uz as $use)
	$userss = $userss.', '.$use;
echo $userss;
?>
</div>
<div class="contact">
Feedback/Suggestions<br /><b>imageroll@live.com</b>
</div>
<div class="project">
A project by<br />
<a href="../blog">Ciaran McNally</a>
</div>
<div class="timerd">
<iframe frameborder="0" frameborder="no" src="timebox.php" width="45" height="40"></iframe>
</div>
<div class="queue">
<iframe frameborder="0" frameborder="no" src="queuebox.php" width="100" height="60"></iframe>
</div>
<div class="feedback">
Get Your<br><a href="feedback.php?name=<? echo $_SESSION['name']; ?>" target="_self">Feedback!</a>
</div>
</body>
</html>
