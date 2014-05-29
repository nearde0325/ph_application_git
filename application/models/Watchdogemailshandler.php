<?php
/*
this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me 
 

*/
class Model_Watchdogemailshandler
{
	//protected $receipt = "norman@missionpc.com.au";


	public function downloadSold($emailAccount,$emailPassword){
		set_time_limit(0);
		//$result = true;
		$mailNumber=0;
		//$cpath=getcwd();
		//$path="/uploaded/";
		$counter=0;
		
		$aymail= new Zend_Mail_Storage_Pop3(
			array( 'host'     => 'pop.gmail.com', 
				   'user'     => $emailAccount,	
			   	   'password' => $emailPassword,
					'ssl'   => 'SSL',
					'port'=> 995
			));
			
		
		foreach($aymail as $counter => $mail){			
			
		//echo $mail->subject;
		if(strpos($mail->subject,"our eBay item sold!")){			
			//echo $mail->subject; 
			$mailNumber = $counter;
			}
		/*		
			if(strpos($mail->subject,"Your eBay item sold!")== true){
					echo $mailNumber = $counter;
					
				}			
		*/
		}
		if(!$mailNumber) return false;
		$msgArr = array();
		$message = $aymail->getMessage($mailNumber);	
		//echo $message['subject'];
		//var_dump($message);
		array_push($msgArr,$message->subject);
		array_push($msgArr,$message->getContent());
		//return $message->getContent();	
		
		//after get the message remote the email 
		
		$aymail->removeMessage($mailNumber);
	
		
		return $msgArr;				
		
		}
		
	public function analyEmail($emailContent){
		$content = $emailContent;
		$posOfBuyer = 0;
		$posEnterAfterBuyer = 0;
		$posOfBuyer = strpos($content,"Buyer:");
		//echo"<br />";
		$posEnterAfterBuyer = strpos($content,"\n",$posOfBuyer);
		//echo "<br />";
		$buyerName = substr($content,$posOfBuyer+6,$posEnterAfterBuyer-$posOfBuyer-6);
		$posOfEmail = strpos($content,"(mailto:",$posEnterAfterBuyer);
		$posOfEmailEnd = strpos($content,")",$posOfEmail);
		$buyerEmail = substr($content,$posOfEmail+8,$posOfEmailEnd-$posOfEmail-8);
		//echo "<br />";
		//echo $buyerEmail;
		$arrBuyer = array(
				'buyerName' => $buyerName,
				'buyerEmail' => $buyerEmail,
						);
		return $arrBuyer;
		
		}
	
	public function analySubject($emailSubject){
		$emailSubject = trim(strtolower($emailSubject));

		$posIdBegin = strpos($emailSubject,"(");
		$posIdEnd = strpos($emailSubject,")");
		$ebayID = substr($emailSubject,$posIdBegin+1,$posIdEnd-$posIdBegin-1);
		
		return $ebayID;		
		
		}
	public function analySales($emailContent){
		
		$content = $emailContent;
		$posOfSoldBegin = strpos($content,"Quantity sold:");
		$posOfSoldEnd = strpos($content,"\n",$posOfSoldBegin);
		$posOfRemainBegin = strpos($content,"Quantity remaining:");
		$posOfRemainEnd = strpos($content,"\n",$posOfRemainBegin);
		
		$qtySold = substr($content,$posOfSoldBegin + strlen("Quantity sold:"),$posOfSoldEnd-$posOfSoldBegin- strlen("Quantity sold:"));
		$qtyRemain = substr($content,$posOfRemainBegin + strlen("Quantity remaining:"),$posOfRemainEnd-$posOfRemainBegin- strlen("Quantity remaining:"));
		
		$arrResult = array (trim($qtySold),trim($qtyRemain));
		
		return $arrResult;
		
	}

	public function sendAlert($itemID,$importantLevel){
	
		date_default_timezone_set('Australia/Melbourne');
		$timenow = date("G");
		
		$mailbody = $itemID;
			
			
		$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
		$mail = new Zend_Mail();
		$mail->setFrom('promotion@lovebargain.com.au', 'promotion@lovebargain.com.au');
		$mail->setBodyHtml($mailbody);
		$mail->addHeader('X-Priority',1);
		$mail->addHeader('X-MSMail-Priority','High');
		$mail->addHeader('Importance','High');
		if($timenow > 9 and $timenow < 18 )
		{
		$mail->addTo("eco6@phonecollection.com.au","Idamo");
		$mail->addTo("eco1@phonecollection.com.au","Norman");	
		} 
		else{
			$mail->addTo("eco6@phonecollection.com.au","Idamo");
			
			$mail->addTo("eco1@phonecollection.com.au","Norman");
			
			
		}
		
		
		$mail->setSubject("StockAlert ".$importantLevel.":".$itemID);
		 
		// if can not send any mail , there is no point to alarm,
		@$mail->send($transport);
	
	}	
	
		
    
}
?>