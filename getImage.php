<?

/*
**This Class is used to retrieve and manipulate images.**

--Usage--
include('getImage.php');
$img = new getImage();

//Add an image from url.
$img->addURL('http://www.google.com/intl/en_ALL/images/logo.gif'); 
$img->fromURL();

//returns true or false if mime type is an image format.
//saves image to folder with specified filename.
$img->verify($filename);

//This displays a thumbnail of the image & cancel button for my app.
$img->display();

//This returns the width and height to a certain area,
//while keeping the area.
$img->getw_h()

*/

class getImage{

	public $contents, $link, $fname, $rightsize;

	//Add a link.
	function addURL($url){
		$this->link = $url;
	}
	
	//Get image from url.
	function fromURL(){
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_POST, 0); 
		curl_setopt($ch,CURLOPT_URL, $this->link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$this->contents = curl_exec($ch); 
		curl_close($ch);
	}

	//Save Img as $name.type.
	function save($name,$type){
		$this->fname = 'IMAGE/'.$name.$type;
		$savefile = fopen($this->fname, 'w');
		fwrite($savefile, $this->contents);
		fclose($savefile);
	}

	//Display image in small box above upload section...
	function display($n,$c){
		echo '<br /><img src="'.$this->fname.'" width="80" height="80" /><br /><b><font color="green">Submitted and Queued!</font></b>';
		echo '<br /><form name="cancelpic" method="post" action="javascript:cancel()"><input type="hidden" id="n" value="'.$n.'"/><input type="hidden" id="c" value="'.$c.'"/><input type="submit" name="cancel" id="cancel" value="cancel"></form>';
	}

	//check maximum file size - 6mb
	function checkfilesize(){	
		$contentLength = 0;			
		$ch = curl_init($this->link);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$data = curl_exec($ch);
		curl_close($ch);

		if (preg_match('/Content-Length: (\d+)/', $data, $matches))
			$contentLength = (int)$matches[1];	//Contains file size in bytes

		if ($contentLength >  6291456)
			return false;

		else	
			return true;
	}
	
	//verify the given link is in fact an image & under 6mb.
	function verify($name){
		$this->link = strip_tags($this->link);		
		if($this->checkfilesize()){
			try{
				$size = getimagesize($this->link);
				if($size["mime"]=='image/gif'){
					$this->save($name,'.gif');
					return true;
				}
				elseif($size["mime"]=='image/jpeg'){
					$this->save($name,'.jpg');
					return true;
				}
				elseif($size["mime"]=='image/png'){
					$this->save($name,'.png');
					return true;
				}
				else	return false;
			}
			catch (Exception $e)	return false;
		}
		else{
			echo '<br><b>File size is greater than 6mb</b>';
			return false;
		}		
	}

	//This returns the "width height" of the image to be displayed.
	function getw_h(){
		$size = getimagesize($this->link);
		$width = $size[0];
		$height = $size[1];
		$allowed_W = 450;
		$allowed_H = 400;
		//This will always keep the image ratio within the max allowed size!
		if($width>$height)
			return ($allowed_W." ".intval($allowed_W*($height/$width)));
      	elseif($height>$width)
			return (intval($allowed_H*($width/$height))." ".$allowed_H);
		else
			return ('400'." ".$allowed_H);
	}
}

?>
