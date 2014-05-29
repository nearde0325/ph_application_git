<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>This Page is for upload the Main image Only <br />
  *This picture show on the eBay search Result and Top left image in the eBay item description <br />
*This Image you choose now MUST NOT with the Water Mark <br />
*This Image Must be .jpg file (Lower Case in the File Name)<br />
*This Image Must be 600X600 px in the size <br />
Product Code :<br />
</p>
<form action="watermarkmaker.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>Please input (DO NOT SCAN) the barcode here: <br />
    <input type="text" name="product_code" id="product_code" value="<?php echo trim($_GET['product_c']);?>" />
    <br />
    <br />
  Choose (.jpg) File </p>
  <br />
    <label for="fileField"></label>
    <input type="file" name="org_file" id="org_file" />
  <p>
    <input type="submit" name="button" id="button" value="Create Water Marks" />
  </p>
</form>
<p>&nbsp; </p>
</body>
</html>
