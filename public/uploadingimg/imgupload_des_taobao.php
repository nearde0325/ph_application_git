<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Description Image</title>
</head>
<body>
<pre>
<?php var_dump($_POST);?><?php var_dump($_FILES);?>
</pre>
<?php
$result="FAIL";

echo $cpath=getcwd();
if(isset($_POST['token']) AND $_POST['token']=='qWeRtYuIoP'){
	 echo "token OK";
	 
	if($_POST['product_code']!=""){
		echo $cpath=getcwd();
		echo $fileName= trim($_POST['product_code']);
			
			for($i=1;$i<11;$i++){
				$fileNameWithNo=$fileName."_".$i.".jpg";
				if($_FILES[$fileName."_".$i]['tmp_name']!=""){
					echo "Not Empty";
					if (move_uploaded_file($_FILES[$fileName."_".$i]['tmp_name'],$cpath."/taobao_extra_img/".$fileNameWithNo)) {
				//begin to do somthing
					$result="SUCCESS";
					}				
				}
			}		

		
		}
	
	}
	
ob_clean();
echo $result;
flush();
ob_flush();	
?>
</body>
</html>