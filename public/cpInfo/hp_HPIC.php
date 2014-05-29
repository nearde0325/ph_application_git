<?php    
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");      
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");      
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");  

    date_default_timezone_set("Australia/Melbourne");   
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://www.w3.org/2000/08/w3c-synd/#">
<meta http-equiv="content-language" content="zh-cn" />
<meta http-equiv="content-type" content="text/html;charset=gb2312" />
<title>[HPIC] Comapny Home Apps</title>
<style>
#newBox {
       position:absolute;
       width:972px;
       height:640px;
       border:1px solid #CCC;
	   font-family:Arial, Helvetica, sans-serif;
       }
#newContent {
       margin:0px;
       width:972px;
       height:640px;
       overflow:hidden;
       }
#newCaption {
       position:absolute;
       left:1px;
       }
ul{
margin:0px;
padding-left:3px;
padding-top:40px;
height:600px;
width:970px;
}


li {      
       padding-left:5px;
       height:27px;
       font-size:12px;
       white-space:nowrap;
       overflow:hidden;
       }
#newCaption a {
       display:block;
       float:left;
       border-right:1px solid #000;
       border-bottom:1px solid #BBB;
       margin-top:0px;
       width:161px;
       height:31px;
       line-height:31px;
       text-align:center;
       font-size:14px;
       color:#fff;
       text-decoration:none;
       font-weight:bold;
	   background-color:#0072c6;
       }
h1{
		font-size:18px;
		color:#0072c6;
		margin-left:20px;
	}
.dvblock{
		display:block;
		height:58px;
		width:165px;
		color:#fff;
		background:#0072c6;
		margin:10px;
		float:left;
		padding:10px;
		font-size:16px;
		font-weight:bold;
		vertical-align:middle;
	}
.dvblockred{
		display:block;
		height:58px;
		width:165px;
		color:#fff;
		background:#903;
		margin:10px;
		float:left;
		padding:10px;
		font-size:16px;
		font-weight:bold;
		vertical-align:middle;
	}
.dvblock a {
	color:#FFF;
	text-decoration:none;
	}
.dvblockred a {
	color:#FFF;
	text-decoration:none;
	}	
	
body{
	margin:0px;

}			   
</style>

</head>
<body onload="window.resizeTo(1005,680)">
<div>

</div>
<div id="newBox">

<div id="newCaption">
<a href="#a" title="">Company News </a>
<a href="#c" title="">Applications</a>
<a href="#b" title="">Facilities</a>
<a href="#d" title="">Online Forms</a>
<a href="#e" title="">Documents</a>
<a href="#f" title="" style="border-right:none;">Training Material</a>

</div>
<div id="newContent">

<ul id="a">
	<h1>Company News And Announcement</h1>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="65%" valign="top">
    <iframe width="100%" height="400" src="http://115.64.171.97:1080/companydocs/updatenews" scrolling="auto" frameborder="0"></iframe>
    </td>
    <td width="35%" valign="top">
    <iframe width="100%" height="220" src="http://115.64.171.97:1080/stock/kakt-notice/shop/HPIC" scrolling="no" frameborder="0"></iframe> 
    <iframe width="100%" height="180" src="http://115.64.171.97:1080/customer/inquery/shop/HPIC" scrolling="no" frameborder="0"></iframe>
    </td>
  </tr>
</table>
    <br />
   <div class="dvblock"> <a href="http://115.64.171.97:1080/companydocs/anno" target="_blank">Company News and Announcement</a></div>
   <div class="dvblock"> <a href="http://115.64.171.97:1080/companydocs/newsletter" target="_blank">New Products</a></div>
   <div class="dvblockred"> <a href="http://115.64.171.97:1080/products/finder-public/shop/HPIC" target="_blank">Online Product Finder</a></div> 
    <div class="dvblock"> <a href="http://115.64.171.97:1080/repaircenter/parts-price/token/<?php echo base64_encode(date("Ymdh"))?>" target="_blank">Repair Parts Price</a></div>    
</ul>


<ul id="b">
	<h1>Website Facilities</h1>
	<div class="dvblock"> <a href="http://www.phonecollection.com.au:2096" target="_blank">Webmail</a></div>
    <div class="dvblock"> <a href="http://forum.phonecollection.com.au" target="_blank">Forum</a></div>
    <div class="dvblock"> <a href="http://stationary.phonecollection.com.au" target="_blank">Stationary Order</a></div>   
    <div class="dvblock"> <a href="http://115.64.171.97:1080/roster/upload-roster/shop/HPIC" >Upload Roster</a></div>
        <div class="dvblock"> <a href="http://115.64.171.97:1080/roster/check-online-roster/shop/HPIC" target="_blank" >Check Online Rosters</a></div> 
</ul>

<ul id="c">
    <h1>Company Applications</h1>
	<div class="dvblock"> <a href="http://115.64.171.97:1080/rma/new/shop/HPIC" target="_blank">RMA System[Return Faulty]</a></div>
    <div class="dvblock"> <a href="http://115.64.171.97:1080/cashaccount/index/shop/HPIC" target="_blank">Cash Account</a></div>
    <div class="dvblock"> <a href="http://115.64.171.97:1080/repaircenter/mainpage/shop/HPIC" target="_blank">Mobile Phone Repair</a></div>
    <div class="dvblock"> <a href="http://115.64.171.97:1080/roster/login/shop/HPIC?token=27c0fd4434344f5348e5539eacb2e7cd" >Staff Attendance</a></div>
    <div class="dvblock"> <a href="http://115.64.171.97:1080/stock/check-shop-kakt/shop/HPIC" target="_blank" >KA/KT Management</a></div>
    <?php
    date_default_timezone_set("Australia/Melbourne");
	$dailyLink = "http://115.64.171.97:1080/salesmonitor/daily-stock-take-list/shop/HPIC";
	$weeklyLink = "";
	$dayToday = date("N");
	if($dayToday == 1){
		$dailyLink = "";
		$weeklyLink ="http://115.64.171.97:1080/salesmonitor/show-weekly-stock-take-diff/shop/HPIC";
		
		}
	?>
    <div class="dvblock"> <a href="<?php echo $dailyLink;?>" target="_blank" >Daily Stock Take<br /><br /><span style="font-size:12px; color:#F00">Not Available on Monday</span></a></div>
    <div class="dvblock"> <a href="<?php echo $weeklyLink;?>" target="_blank" >Weekly Stock Take<br /><br />
      <span style="font-size:12px; color:#F00">Do this on  Monday</span></a></div> 
    
      <?php 
	  $uQty = file_get_contents("http://115.64.171.97:1080/products/get-unhandled-count/shop/HPIC");
	  if((int)$uQty >20){
	  ?>
      <div class="dvblockred"> <a href="http://115.64.171.97:1080/products/extra-order/shop/HPIC" target="_blank" >Sold Out Items<br /><br />
      <span style="font-size:13px; color:#FFF"><?php echo $uQty." Items To Do !!"; ?></span></a></div>
      <?php }else {?>
          <div class="dvblock"> <a href="http://115.64.171.97:1080/products/extra-order/shop/HPIC" target="_blank" >Sold Out Items<br /><br />
      <span style="font-size:12px; color:#F00">Complete before Monday</span></a></div>
      <?php }?>
<div class="dvblock"> <a href="http://115.64.171.97:1080/roster/start-here/shop/HPIC"  target="_blank" >New Staff<br />Registration <br /><br />
 </a></div> 
<div class="dvblock"> <a href="http://115.64.171.97:1080/products/shop-analysis-public/shop/HPIC"  target="_blank" >Shop Analysis <br /><br />
 </a></div> 
    <!--<div class="dvblock"> <a href="#">Retrieve Store Password</a></div>-->
 
</ul>

<ul id="d">
    <h1>Online Forms</h1>
	<div class="dvblock"><a href="https://docs.google.com/spreadsheet/viewform?formkey=dF9zeE9xYmhBa2o0ZlkzbFpNLVYyS1E6MA" target="_blank">Offline Voucher/Credit Note Form</a></div>
    <div class="dvblock"><a href="https://docs.google.com/spreadsheet/viewform?formkey=dHNSN0M1dFBvZTJCckVocGpyc3c3aGc6MQ" target="_blank">Staff Phone Call Record Form</a></div>
    <div class="dvblock"><a href="https://docs.google.com/spreadsheet/viewform?formkey=dHVFWE5lYnQwbkxqbFZSX0lWZkEyUnc6MQ" target="_blank">Staff Purchase</a></div>
    <div class="dvblock"><a href="https://docs.google.com/spreadsheet/viewform?formkey=dHlrbnNTOU4xcmJ5ZE9nZ0NreGZwR0E6MQ" target="_blank">VIP Customer Registration From</a></div>
    <div class="dvblock"><a href="https://docs.google.com/spreadsheet/viewform?formkey=dGZvX1I4Y3M4QjdLYTZUQkd6TkV3NEE6MA" target="_blank">Customer Request Form</a></div>
    <div class="dvblock"><a href="https://docs.google.com/forms/d/1gBq5TW8U6tiCeSdsrsv-nFJPPPp_0igvDBNvmbMxDfA/viewform" target="_blank">Online Leave Application  Form</a></div></ul>
<ul id="e">
    <h1>Company Documents</h1>
	<div class="dvblock"><a href="http://115.64.171.97:1080/companydocs/listdocs#6" target="_blank">Company Policy</a></div>
    <div class="dvblock"><a href="http://115.64.171.97:1080/companydocs/listdocs#7" target="_blank">Product Information</a></div>
    <div class="dvblock"><a href="http://115.64.171.97:1080/companydocs/listdocs#8" target="_blank">Hand book and Manual</a></div>
    <div class="dvblock"><a href="http://115.64.171.97:1080/companydocs/listdocs#11" target="_blank">Company Operation Form(Downloadable)</a></div>
    <div class="dvblock"><a href="http://115.64.171.97:1080/companydocs/listdocs#12" target="_blank">Operational Procedure or Guidelines</a></div>

</ul>
<ul id="f">
    <h1>Training Material</h1>
	<div class="dvblock"><a href="http://www.phonecollection.com.au/index.php/staff-login" target="_blank">Login to Website to Access</a></div>

</ul>

</div>

</div>
</body>
</html>