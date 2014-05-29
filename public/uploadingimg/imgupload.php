<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<pre>
<?php var_dump($_POST);?><?php var_dump($_FILES);?>
</pre>
<?php
$result="FAIL";

echo $cpath=getcwd();
if(isset($_POST['token']) AND $_POST['token']=='AsDfGhJkL'){
	 echo "token OK";
	 
	if($_POST['product_code']!=""){
		$cpath=getcwd();
		$fileName= trim($_POST['product_code'])."_0.jpg";
			if (move_uploaded_file($_FILES['org_file']['tmp_name'],$cpath."/home_img/".$fileName)) {
			//begin to do somthing
			$result="SUCCESS Upload Home Image";
			}
			if (move_uploaded_file($_FILES['ebay_file']['tmp_name'],$cpath."/ebay_gallery_img/".$fileName)) {
			//begin to do somthing
			$result="SUCCESS Upload Au Main Gallery Image";
			}
		}
	
	}
	
ob_clean();
echo $result;
?>
This is the Link you should use for Main Gallery Image
<input name="gallery" type="text" value="http://
<?php
echo $_SERVER['SERVER_NAME']."/".$cpath."/ebay_gallery_img/".$fileName;
?>
" />
<?
flush();
ob_flush();	
?>
</body>
</html>