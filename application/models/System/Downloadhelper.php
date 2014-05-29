<?php
class Model_System_Downloadhelper {
	
	public static function downloadUrl($url){
	
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL,$url);
			
		$content = curl_exec($ch);
		$output = "";
	
		//Checking Error, The return is string , which will be named as file
		if($content === FALSE){
				
			$output = "CURL ERROR".curl_error($ch);
		}
		else
		{
			$output = $content;
		}
		curl_close($ch);
		return $output;
	}
	
}

?>