<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
//move the Original image from  temp folder to Home folder 
$err_flag = 0;
$fileName= trim($_POST['product_code'])."_0.jpg";

$cpath = getcwd();
//$cpath."/org_image/home_image_org/".$fileName;
if(!copy($_POST['org_file'],$cpath."/org_image/home_image_org/".$fileName)){
	$err_flag = 1;
	}
if(!copy($_POST['ebay_file'],$cpath."/gallery_image/lovebargain/".$fileName)){
	$err_flag = 2;
	}
echo "ERROR FLAG (0 is no error):".$err_flag;	
//move the au image from temp folder to au folder 



//http://www.newgenerationgroup.com.au/product_img/imgupload.php

//var_dump($_POST);

		$uploadResult=false;
		$uploadHandlerUrl="http://www.newgenerationgroup.com.au/product_img/imguploadau.php";
  	    $cpath=getcwd();
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_VERBOSE, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    	curl_setopt($ch, CURLOPT_URL,$uploadHandlerUrl);
    	curl_setopt($ch, CURLOPT_POST, true);
    // same as <input type="file" name="file_box">
    	$post = array(
			"token"=>"AsDfGhJkL",
			"uploadurl"=>$uploadUrl,
			"product_code" => $_POST['product_code'],
        	"org_file"=>"@".$_POST['org_file'],
			"ebay_file"=>"@".$_POST['ebay_file'],
			
    	);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    	echo $response = curl_exec($ch);		
		curl_close($ch)
?>
</body>
</html>