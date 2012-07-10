<?php
//This verifies a user who is logging in.

include "ImagerollDB.php";
$roll = new Imageroll();
$roll->connect();

session_start();

require_once('recaptchalib.php');
$privatekey = "XXXXXXXXXXXXXXXXXXXXX";
$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

if((!$resp->is_valid)){
	header("location:index.php?error=captcha");
} 
else{
	$capture = 'cpt_'.rand(100000,10000000);
	if(isset($_POST['name'])&&($_SESSION['name']=='')){
		$user = $_POST['name'];
		$user = htmlentities($user);
		if(strlen($user) > 20){
			header("location:index.php?error=illegalchars");
		}
		else{
			while($_SESSION['name'] == ''){
				$x = $roll->getActiveUsers();
				
				//if user already exists append a random int to end.
				if(in_array($user, $x)){
					$nu = rand(0,9);
					$user = $user.$nu;
				}
				elseif(!in_array($user, $x)){
					$se = rand(100000,10000000);
					$_SESSION['code'] = $se;
					$_SESSION['name'] = $user;
					$_SESSION['capture'] = $capture;
					$ip = $_SERVER["REMOTE_ADDR"];
					$roll->insertActiveUser($user,$se,$ip,$_SERVER['REQUEST_TIME'],$capture);
				}
				header("location:main.php");
			}
		}
	}
}
