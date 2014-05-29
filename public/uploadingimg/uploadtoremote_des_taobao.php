<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
//http://www.newgenerationgroup.com.au/product_img/imgupload.php
//var_dump($_POST);
		$uploadResult=false;
		$fileName = trim($_POST['product_code']);
		$uploadHandlerUrl="http://www.newgenerationgroup.com.au/product_img/imgupload_des_taobao.php";
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
			"token"=>"qWeRtYuIoP",
			//"uploadurl"=>$uploadUrl,
			"product_code" => $_POST['product_code'],
        	//"org_file"=>"@".$_POST['org_file'],
			//"ebay_file"=>"@".$_POST['ebay_file'],
			
    	);
			for($i=1;$i<11;$i++){			
				if($_POST[$fileName."_".$i]!=""){
					$post[$fileName."_".$i] = "@".$_POST[$fileName."_".$i];
				}
			}		
		
		
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    	echo $response = curl_exec($ch);		
		//var_dump($post);
?>
</body>
</html>