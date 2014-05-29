<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload Description Image</title>
</head>
<body>
<p>You may upload Upto 10 Image <br />
  Currently No Error Correction , <br />
  So Make Sure what you upload <br />
  <br />
  600px by 600px JPG File<br />
Make Sure you upload according Orders</p>
<p> <br />
  <br />
</p>
<form action="watermarkmaker_des.php"  method="post" enctype="multipart/form-data" name="form1" id="form1">
  Product Code :
  <input name="product_code" type="text" id="product_code" value="<?php echo trim($_GET['product_c']);?>" />
<input name="token" type="hidden" value="qWeRtYuIoP">
  <br />
  <br />
  Image 1:
<input type="file" name="fileField1" id="fileField1" />
  <br />
  <br />
    Image 2: 
    <input type="file" name="fileField2" id="fileField2" />
  <br />
    <br />
  Image 3:
<input type="file" name="fileField3" id="fileField3" />
  <br />
    <br />
  Image 4:
<input type="file" name="fileField4" id="fileField4" />
  <br />
    <br />
  Image 5:
<input type="file" name="fileField5" id="fileField5" />
  <br />
    <br />
  Image 6:
<input type="file" name="fileField6" id="fileField6" />
  <br />
    <br />
  Image 7:
<input type="file" name="fileField7" id="fileField7" />
  <br />
    <br />
  Image 8:
<input type="file" name="fileField8" id="fileField8" />
  <br />
    <br />
  Image 9:
<input type="file" name="fileField9" id="fileField9" />
  <br />
    <br />
  Image 10:
<input type="file" name="fileField10" id="fileField10" />
  <br />
  <br />
  <br />
  <input type="submit" name="button" id="button" value="Submit" />
<br />
</form>
</body>
</html>
