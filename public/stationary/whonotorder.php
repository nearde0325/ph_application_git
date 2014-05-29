<pre>
<?php
include('../config/config.inc.php');

date_default_timezone_set('Australia/Melbourne');
			
		$dateBegin = "";
		$dateEnd = "";
		$thisMonday = "";
		
		if(intval(date("w"))==1){
			$thisMonday = date("Y-m-d H:i:s",strtotime("this monday"));
		}
		else{
			$thisMonday = date("Y-m-d H:i:s",strtotime("last monday"));
		}

//if ($_GET['password'] == 'ZGBDU2SD8JFNQ3YB0ZPHRPA5')
	
	$rows = Db::getInstance()->executeS("SELECT DISTINCT `id_customer` FROM `"._DB_PREFIX_."orders` WHERE `date_add` >= '".$thisMonday."' ");
	
	$arrShop = array("2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","29");
	$arrResult = array();
	$arrShopOrdered = array("b");

	foreach($rows as $key =>$value){
		$arrShopOrdered[]= intval($value['id_customer']);
	}
	//var_dump($arrShopOrdered);
	
	foreach($arrShop as $k => $v){
		if(!array_search($v,$arrShopOrdered)){
			$arrResult[]= $v;
			}	
	}
	
	$to      = 'eco1@phonecollection.com.au';
	$subject = 'Time to Order Your Stationary';
	$message = 'Hi Shop, we still did not receive any stationary order from you this week, please make sure you complete the order by today, if you need anything, if you have everything ready, just ignore this message ';
	$headers = 'From: stationary@phonecollection.com.au' . "\r\n" .
    'Reply-To: stationary@phonecollection.com.au' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
	
	foreach($arrResult as $k2 => $v2){
		switch($v2){
			case 2 :{mail("arndale@phonecollection.com.au",$subject,$message,$headers);break;}
			case 3 :{mail("brimbank@phonecollection.com.au",$subject,$message,$headers);break;}
			case 4 :{mail("boxhill@phonecollection.com.au",$subject,$message,$headers);break;}
			case 5 :{mail("bayside@phonecollection.com.au",$subject,$message,$headers);break;}
			case 6 :{mail("baysideic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 7 :{mail("cranbourne@phonecollection.com.au",$subject,$message,$headers);break;}
			case 8 :{mail("colonnades@phonecollection.com.au",$subject,$message,$headers);break;}
			case 9 :{mail("corio@phonecollection.com.au",$subject,$message,$headers);break;}
			case 10 :{mail("epping@phonecollection.com.au",$subject,$message,$headers);break;}
			case 11 :{mail("fg@phonecollection.com.au",$subject,$message,$headers);break;}
			case 12 :{mail("knox@phonecollection.com.au",$subject,$message,$headers);break;}
			case 13 :{mail("northland@phonecollection.com.au",$subject,$message,$headers);break;}
			case 14 :{mail("parkmore@phonecollection.com.au",$subject,$message,$headers);break;}
			case 15 :{mail("southlandic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 16 :{mail("werribee@phonecollection.com.au",$subject,$message,$headers);break;}
			case 17 :{mail("werribeeic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 18 :{mail("westlake@phonecollection.com.au",$subject,$message,$headers);break;}
			case 19 :{mail("watergardenic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 20 :{mail("watergardenpc@phonecollection.com.au",$subject,$message,$headers);break;}
			case 21 :{mail("chadstoneic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 22 :{mail("colonnadesic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 23 :{mail("doncasteric@phonecollection.com.au",$subject,$message,$headers);break;}
			case 24 :{mail("parkmoreic@phonecollection.com.au",$subject,$message,$headers);break;}
			case 25 :{mail("bsxprepair@phonecollection.com.au",$subject,$message,$headers);break;}
			case 26 :{mail("fountaingateic@phonecollection.com.au",$subject,$message,$headers);break;}			
			case 29 :{mail("highpointic@phonecollection.com.au",$subject,$message,$headers);break;}
			
			}
		}
	
//$rows="lll";
//var_dump($arrResult);
//var_dump($rows);





?>
</pre>