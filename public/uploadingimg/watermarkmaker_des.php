<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WaterMark Maker</title>
</head>
<body>
<?php
$errorMessage = "";
$result="FAIL";
$cpath=getcwd();
$counter = 1;

$rand = mt_rand();

$orgFileTempName="org_temp".strval($rand).".jpg";
$ebayLovebargainGalleryTempName = "lovebargain_gallery".strval($rand).".jpg";
$ebayShiplocalGalleryTempName =  "shiplocal_gallery".strval($rand).".jpg";
$taobaoGalleryTempName =  "taobao_gallery".strval($rand).".jpg";
//
if(!empty($_POST)){

// check the product code 
if(trim($_POST['product_code'])==""){
	
	$errorMessage="Product Code Can Not be Empty";
	}
$fileName= trim($_POST['product_code']);	
// copy images from Post to Original Forlder , remember the count
			for($i=1;$i<11;$i++){
				$fileNameWithNo=$fileName."_".$i.".jpg";
				if($_FILES['fileField'.$i]['tmp_name']!=""){
					if (move_uploaded_file($_FILES['fileField'.$i]['tmp_name'],$cpath."/org_image/extra_image_org/".$fileNameWithNo)) {
				//begin to do somthing
					$result="SUCCESS";
					$counter = $i;
					
							try{
			waterMark("/org_image/extra_image_org/".$fileNameWithNo,"au_".$fileNameWithNo,"watermark_kmobile_des.png");
			waterMark("/org_image/extra_image_org/".$fileNameWithNo,"usa_".$fileNameWithNo,"watermark_shiplocal_des.png");
			waterMark("/org_image/extra_image_org/".$fileNameWithNo,"taobao_".$fileNameWithNo,"watermark_taobao_des.png");
		}
		catch(Exception $e){
			
			$errorMessage = "Water Mark Making Fail...";
			
			}
					
					}				
				}
			}

//create upload to AU

?>
<form method="post" enctype="multipart/form-data" action="uploadtoremote_des_au.php"  >
        <div>Original File : Click each button below to upload the image , then &quot;go back&quot; click another one <br />
  		 Uploading to remove will take a while, please wait <em>patiently</em><br />  </div>   
          <input type="hidden" name="token" value="AsDfGhJkL" />
          <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
		<?php
		for($j= 1;$j<=$counter;$j++){
			$auFileNameWithNo = "au_".$fileName."_".$j.".jpg";
			echo "<img src=\"./tempimg/$auFileNameWithNo\" width=\"150\" height=\"150\" />";
			echo "<input type=\"hidden\" name=\"".$fileName."_$j\" value=\"$cpath/tempimg/$auFileNameWithNo\" >";
			}
		?>         
  <input name="submit1" id=name="submit1" type="submit" value="Upload to Remote Au Server" />          
</form>


<form method="post" enctype="multipart/form-data" action="uploadtoremote_des_usa.php"  >
        <div>Original File : Click each button below to upload the image , then &quot;go back&quot; click another one <br />
  		 Uploading to remove will take a while, please wait <em>patiently</em><br />  </div>   
          <input type="hidden" name="token" value="AsDfGhJkL" />
          <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
		<?php
		for($j= 1;$j<=$counter;$j++){
			$usaFileNameWithNo = "usa_".$fileName."_".$j.".jpg";
			echo "<img src=\"./tempimg/$usaFileNameWithNo\" width=\"150\" height=\"150\" />";
			echo "<input type=\"hidden\" name=\"".$fileName."_$j\" value=\"$cpath/tempimg/$usaFileNameWithNo\" >";
			}
		?>         
  <input name="submit1" id=name="submit1" type="submit" value="Upload to Remote Au Server" />          
</form>


<form method="post" enctype="multipart/form-data" action="uploadtoremote_des_taobao.php"  >
        <div>Original File : Click each button below to upload the image , then &quot;go back&quot; click another one <br />
  		 Uploading to remove will take a while, please wait <em>patiently</em><br />  </div>   
          <input type="hidden" name="token" value="AsDfGhJkL" />
          <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
		<?php
		for($j= 1;$j<=$counter;$j++){
			$taobaoFileNameWithNo = "taobao_".$fileName."_".$j.".jpg";
			echo "<img src=\"./tempimg/$taobaoFileNameWithNo\" width=\"150\" height=\"150\" />";
			echo "<input type=\"hidden\" name=\"".$fileName."_$j\" value=\"$cpath/tempimg/$taobaoFileNameWithNo\" >";
			}
		?>         
  <input name="submit1" id=name="submit1" type="submit" value="Upload to Remote Au Server" />          
</form>
<?php


}
else{

$errorMessage =" You need to upload something!!";	
}	

echo $errorMessage;



function waterMark($filename,$watermarkFilename,$thewatermark){
	
$cpath = getcwd();
	

$photo = imagecreatefromjpeg($cpath."/".$filename);
$watermark = imagecreatefrompng($cpath."/".$thewatermark);
// This is the key. Without ImageAlphaBlending on, the PNG won't render correctly.
imagealphablending($photo, true);
// Copy the watermark onto the master, $offset px from the bottom right corner.
$offset = 0;
imagecopy($photo, $watermark, imagesx($photo) - imagesx($watermark) - $offset, imagesy($photo) - imagesy($watermark) - $offset, 0, 0, imagesx($watermark), imagesy($watermark));
// Output to the browser - please note you should save the image once and serve that instead on a production website.
//header("Content-Type: image/jpeg");
imagejpeg($photo,$cpath."/tempimg/".$watermarkFilename);		
				
}


?>
</body>
</html>