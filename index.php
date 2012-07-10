<?
session_start();

?>
<!DOCTYPE html>
<html>
<head>
<title>Imageroll!</title>
<meta charset="UTF-8">
<link rel="stylesheet" href="imagerolli.css" type="text/css" />
<script type="text/javascript" src="script/jquery.js"></script>
<script type="text/javascript" src="script/jquery.validate.js"></script>
<script>
$(document).ready(function(){
    $("#user").validate();
});
</script>
</head>
<body>
<div class="header">
<img height="20%" width="70%" src="Img/logo.png"/>
</div>
<div class="main">
<form action="verify.php" method="post" class="user" id="user">
<div class="main2">
<hr>
<br>
<p>Welcome to Imageroll.</p>
<p>The idea here is simple. Image sharing and feedback with a twist.<br />All the content here is user generated.<br><br><b>- This site may contain adult material. -</b><br /></p>

<?
if(isset($_GET['error'])){
	if($_GET['error'] == 'illegalchars'){
		echo '<font color="red">Please use a different username.</font><br>';
	}
	if($_GET['error'] == 'captcha'){
		echo '<font color="red">The captcha was wrong or not completed.</font><br>';
	}
	if($_GET['error'] == 'login'){
		session_unset();
		echo '<font color="red">Login error.</font><br>';
	}
}
if(isset($_SESSION['name'])){
	echo "<p>You are already logged in ".$_SESSION['name']."!</p>";
	echo '<a href="main.php" target="_self"><h2>Continue...</h2></a><br /><hr>';
}
else{
	echo '<br /><input type="text" name="name" class="required" placeholder="    Pick a Username!" size="17" /><input type="submit" value="ENTER" /><br /><br /><hr></div><br />';
	require_once('recaptchalib.php');
    $key = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
    echo recaptcha_get_html($publickey);
	echo '<p align="center">Please fill in the captcha. This helps avoid spam.</p></form>';
}
?>
</div>
</body>
</html>
