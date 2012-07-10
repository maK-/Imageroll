<?
include('getimage.php');
include('urlcheck.php');
include ('ImagerollDB.php');
$roll = new Imageroll();
$roll->connect();

/*
-check the image link, create the image.
-Insert the location of the image into the queue if the user doesn't already have one in it.
-Return the image and/or errors.
*/
$name = mysql_real_escape_string($_GET['username']);
$question = mysql_real_escape_string($_GET['question']);
$url = mysql_real_escape_string($_GET['url']);
$code = mysql_real_escape_string($_GET['code']);

//check if url is valid.
$urlcheck = new checkURL();
if($urlcheck->is_valid_url($url)){
	$_SESSION['url']=$url;
	if($roll->isActive($name,$code)){
		if($roll->hasAlreadyUploaded($code, $name)){
			echo '<br /><b><font color="red">Only one image in the queue. Please Wait...</font></b>';
			echo '<br /><form name="cancelpic" method="get" action="javascript:cancel()"><input type="hidden" id="n" value="'.$name.'"/><input type="hidden" id="c" value="'.$code.'"/><input type="submit" name="cancel" id="cancel" value="cancel"></form>';
		}
		else{
			$filename = $roll->filenameGen($name,$code);
			$img = new getImage();
			$img->addURL($url);
			$img->fromURL();
			
			//only run if it's a verified image.
			if($img->verify($filename)){
				$loc = mysql_real_escape_string($img->fname);
				$roll->insertToLog($name,$question,$url);
				$firstup = $roll->firstUploaded();				

				//if first image in queue
				$amount = $roll->QueueAmount();
				$tim = $_SERVER['REQUEST_TIME'] + 59;

				//if no images in q get current time, if there is use time from first.
				if($amount == 0){
					$width_height = $img->getw_h();
					$wh = explode(" ",$width_height);
					$roll->insertToQueue($name,$code,$question,$loc,$tim,$url,$wh[0],$wh[1]);
				}
				else{
					$width_height = $img->getw_h();
					$wh = explode(" ",$width_height);
					$roll->insertToQueue($name,$code,$question,$loc,$firstup,$url,$wh[0],$wh[1]);
				}					
				//Display the image...
				$img->display(stripslashes($name),stripslashes($code));
			}
			else{
				echo '<br /><b><font color="red">Not a valid Url!</font></b>';
			}
		}
	}
	else{
		echo '<br /><b><font color="red">Invalid user...</font></b>';
	}
}
else{
	echo'<br /><b><font color="red">Invalid Url...</font></b>';
}
?>



	
