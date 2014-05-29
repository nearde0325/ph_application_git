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
// get the image ,
if (move_uploaded_file($_FILES['org_file']['tmp_name'],$cpath."/tempimg/".$orgFileTempName)) {
	$result= "SUCCESS";
	$checker = getimagesize($cpath."/tempimg/".$orgFileTempName);
	if($checker[0]!=600 or $checker[1]!=600 or $checker[2]!=2 ){
		
		$errorMessage ="Either the size is Not accept Or the File Type is Not JPG";
		}
	else{
		//Everything OK  , Make the Water mark File 
		try{
			waterMark($orgFileTempName,$ebayLovebargainGalleryTempName,"watermark_kmobile.png");
			waterMark($orgFileTempName,$ebayShiplocalGalleryTempName,"watermark_shiplocal.png");
			waterMark($orgFileTempName,$taobaoGalleryTempName,"watermark_taobao.png");
		}
		catch(Exception $e){
			
			$errorMessage = "Water Mark Making Fail...";
			
			}
		//Now , show some of the pic we done see if we happy with that 
		
?>		
		
        
        
<form method="post" enctype='multipart/form-data' action="uploadtoremoteau.php"  >
        <div>Original File : Click each button below to upload the image , then &quot;go back&quot; click another one <br />
          Uploading to remove will take a while, please wait <em>patient</em>ly<br />
        <input type="hidden" name="token" value="AsDfGhJkL" />
        <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
        <input type="hidden" name="org_file" value="<?php echo $cpath."/tempimg/".$orgFileTempName ?>" />
		<img src="<?php echo "./tempimg/".$orgFileTempName ?>" /><br />
        WaterMark File for eBay Au<br />        
        <img src="<?php echo "./tempimg/".$ebayLovebargainGalleryTempName ?>" /><br />
        <input type="hidden" name="ebay_file" value="<?php echo $cpath."/tempimg/".$ebayLovebargainGalleryTempName ?>">
        <br />
        <br />
        <br />
<input name="submit1" id=name="submit1" type="submit" value="Upload to Remote Au Server" />
</div>

		</form>

        <form method="post" enctype='multipart/form-data' action="uploadtoremoteusa.php"  >
        <div>
          <p>&nbsp;</p>
          <p><br />
        <input type="hidden" name="token" value="AsDfGhJkL" />
        <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
        <br />
        WaterMark File for eBay USA (shiplocal)<br />        
        <img src="<?php echo "./tempimg/".$ebayShiplocalGalleryTempName ?>" /><br />
        <input type="hidden" name="ebay_file" value="<?php echo $cpath."/tempimg/".$ebayShiplocalGalleryTempName ?>">
        <br />
        <br />
<input name="submit1" id=name="submit1" type="submit" value="Upload to Remote USA Server" />
          </p>
        </div>

		</form>
 
         <form method="post" enctype='multipart/form-data' action="uploadtoremotetaobao.php"  >
        <div>
          <p>&nbsp;</p>
          <p><br />
        <input type="hidden" name="token" value="AsDfGhJkL" />
        <input type="hidden" name="product_code" value="<?php echo trim($_POST['product_code']);?>" />
        <br />
        WaterMark File for Taobao<br />        
        <img src="<?php echo "./tempimg/".$taobaoGalleryTempName ?>" /><br />
        <input type="hidden" name="ebay_file" value="<?php echo $cpath."/tempimg/".$taobaoGalleryTempName ?>">
        <br />
        <br />
<input name="submit1" id=name="submit1" type="submit" value="Upload to Remote TAOBAO" />
          </p>
        </div>

		</form>       
			
<?php		
		}	
		
	}
	
//check image size 

//check image type 

//show two picture 	
	
}
else{

$errorMessage =" You need to upload something!!";	
}	

echo $errorMessage;



function waterMark($filename,$watermarkFilename,$thewatermark){
	
$cpath = getcwd();
	

$photo = imagecreatefromjpeg($cpath."/tempimg/".$filename);
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