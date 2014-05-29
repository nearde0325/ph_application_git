<?php
/*
this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me 
 

*/
class Model_Emailshandler
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
			array( 'host'     => 'mail.lovebargain.com.au', 
				   'user'     => $emailAccount,	
			   	   'password' => $emailPassword				
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
		$subjectArr = explode(" ",$emailSubject);
		//echo "<pre>";
		//var_dump($subjectArr);
		//echo "</pre>";
		
		$thePath="";
		$modelfound= false;
		$brandOfPhone = array('apple','Samsung');
		$modelOfPhone = array('iphone','S2 SII','Galaxy S2');
		//$attributes = array('case','cable','car','combo','stand','desktop');
		
		foreach($subjectArr as $key =>$value){
			if(in_array($value,$brandOfPhone)){
				$thePath ="/".$value;
				}
			else
			{
				$thePath="";
				
				//record in the system 
				
				}	
			
			}
			
		foreach($subjectArr  as $key =>$value){
			if(in_array($value,$modelOfPhone)){
				$modelfound = true;
				switch($value){
					case "iphone":
						$thePath = "/apple/iphone";
						break;
					case "ipad":
						$thePath = "/apple/ipad";
						break;
					case "itouch":
						$thePath = "/apple/itouch";
						break;
					case "n7000":
						$thePath = "/samsung/n7000";
						break;
					case "i9300":
						$thePath = "/samsung/i9300";
						break;
					case "i9100":
						$thePath = "/samsung/i9100";
						break;		
					case "S2 SII":
						$thePath = "/samsung/i9100";
						break;
					case "Galaxy S2":
						$thePath = "/samsung/i9100";
						break;
	
					}
				
				}	
			
			}			

        /*
		foreach($subjectArr as $key =>$value){
			if(in_array($value,$attributes) and $modelfound){
				
				$thePath .="/".$value;
				}
			else
			{
				//do nothing
				}	
			
			}
				
		*/
		/*
		$prefixArr = array('genuine','original');
		$brandOfVender = array('dexim','x-doria','otterbox');
		
		$price_range_1 = "15";
		$price_range_2 = "25";
		*/
		
		$thePath .="/emailtemplate.html";
		
		
		return $thePath;
		
		
		}
	public function sendPromotions($cusName,$cusEmail,$fileName){
		
	        date_default_timezone_set('Australia/Melbourne');
			$timenow = date("D Y-m-d g:i a");	
			$mailbody = "The following Row Data File Download failed,".$cusName." on ".$timenow;
			$cpath= getcwd();
			$totalfilename = $cpath."/emails".$fileName;
			$fl = fopen($totalfilename,"r");
			$strlong = fread($fl,filesize($totalfilename));
			//$strlong = self::buildEmail($emailStr, $cusName, $vocherCode)
			$vocherCode = "7OLCXU6N";
			$mailbody = self::buildEmail($strlong, $cusName, $vocherCode);
			//$config = array('auth' => 'login','username' => 'promotion@.lovebargaincom.au',//
			//						  'password' => 'mon80ash');
			//$transport = new Zend_Mail_Transport_Smtp('mail.lovebargain.com.au', $config);//
			
			
			$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
			$mail = new Zend_Mail();   
      		$mail->setFrom('promotion@lovebargain.com.au', 'promotion@lovebargain.com.au');
	  		$mail->setBodyHtml($mailbody);
      		$mail->addTo(trim($cusEmail),trim($cusName));
   
	        $mail->setSubject('Little Thanks from Lovebargain (eBay:K-mobile)');
   
	        // if can not send any mail , there is no point to alarm,
			@$mail->send($transport);		
		
		}
	public function sendNormalEmail($receiver,$subject,$mailbody,$replyto = null){
	       
		    date_default_timezone_set('Australia/Melbourne');
			$timenow = date("D Y-m-d g:i a");			
			@$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
			$mail = new Zend_Mail();   
      		$mail->setFrom('do-not-reply@phonecollection.com.au', 'do-not-reply@phonecollection.com.au');
      		if(isset($replyto)){
      		$mail->setReplyTo($replyto,$replyto);	
      		}
	  		$mail->setBodyHtml($mailbody);
      		$mail->addTo(trim($receiver),trim($receiver));
   
	        $mail->setSubject($subject);
   
	        // if can not send any mail , there is no point to alarm,
			@$mail->send($transport);		
		
		}	
		
	public function sendNormalEmailPh($receiver,$subject,$mailbody,$replyto = null){
		
		date_default_timezone_set('Australia/Melbourne');
		$timenow = date("D Y-m-d g:i a");
		/*$config = array(
				'auth'=>'login',
				'username' => 'do-not-reply@phonecollection.com.au',
				'password' => '051788office',
				'port' => 587
				);
		*/		
		@$transport = new Zend_Mail_Transport_Smtp('mail.bigpond.com');
		$mail = new Zend_Mail();
		$mail->setFrom('do-not-reply@phonecollection.com.au', 'do-not-reply@phonecollection.com.au');
		if(isset($replyto)){
			$mail->setReplyTo($replyto,$replyto);
		}
		$mail->setBodyHtml($mailbody);
		$mail->addTo(trim($receiver),trim($receiver));
		 
		$mail->setSubject($subject);
		 
		// if can not send any mail , there is no point to alarm,
		@$mail->send($transport);		
		
		
		
	}	
	public function sendAttachEmail($receiver,$subject,$mailbody,$folder,$attachFile,$replyto = null){
		
		date_default_timezone_set('Australia/Melbourne');
		$timenow = date("D Y-m-d g:i a");
		@$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
		$mail = new Zend_Mail();
		$at = $mail->createAttachment(file_get_contents($folder.$attachFile));
		$at->filename = $attachFile;
		$mail->setFrom('do-not-reply@phonecollection.com.au', 'do-not-reply@phonecollection.com.au');
		if(isset($replyto)){
			$mail->setReplyTo($replyto,$replyto);
		}
		$mail->setBodyHtml($mailbody);
		$mail->addTo(trim($receiver),trim($receiver));
		 
		$mail->setSubject($subject);
		 
		// if can not send any mail , there is no point to alarm,
		@$mail->send($transport);		
		
		
	}	
	public function buildEmail($emailStr,$cusName,$vocherCode){
				
		$emailStr = str_replace("[thecustomername]",$cusName,$emailStr);
		$emailStr = str_replace("[thevochercode]",$vocherCode,$emailStr);
		//$emailStr = str_replace("[thecustomername]",$cusName,$emailStr);
		$emailStr = str_replace("[dateexpireformat1]",self::buildExpireDate(1),$emailStr);
		$emailStr = str_replace("[dateexpireformat2]",self::buildExpireDate(2),$emailStr);
		
		return $emailStr;
		
		}
	public function buildExpireDate($format){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = date("Y-m-d");
		$resultDate="";
		$newdate = new DateTime($dateToday);
		$newdate->add(new DateInterval('P7D'));
		
		if($format==1){
			$resultDate = date_format($newdate,'d/m/Y');
			}
		else{
			$resultDate = date_format($newdate,'jS , M , Y');
			}	
		
		return $resultDate;
		}			
	public function deleteEmail(){
		
		
		
	}	
	public function sendLateEmail($managerEmail,$staffName){
		
		$mailBody = "Dear Manager , your Staff ".$staffName."came late than his/her scheduled shift more than twice this week.Please look into this matter.";
		self::sendNormalEmail($managerEmail,"Staff Attendance Record Need Your attention", $mailBody);
	}	
    public function sendShiftChangeEmail($managerEmail,$staffName,$shiftStart,$shiftEnd){
    	$mailBody = "Dear Manager , your Staff ".$staffName." has change the Shift Time Start at ".$shiftStart." to ".$shiftEnd.". Please goto online roster to confirm this change.";
    	self::sendNormalEmail($managerEmail,"Staff Shift Change", $mailBody);
    }
    
    public function sendNewStaffEmail($firstName,$lastName,$gender,$email,$preName,$dob,$address,$suburb,$state,$postcode,$mobile,$shopCode,$managerName){
    	
    	$mailBody =
    	"
    	<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\">
  <tr>
    <td colspan=\"2\"><h1 style=\"color:#069\">Registration (Basic Information)</h1></td>
  </tr>
  <tr>
    <td>Your Email (Read Only Field):</td>
    <td>{$email}</td>
  </tr>
  <tr>
    <td>Given Names:</td>
    <td>{$firstName}</td>
  </tr>
  <tr>
    <td>Surname:</td>
    <td>{$lastName}</td>
  </tr>
  <tr>
    <td>Prefferred Name:</td>
    <td>{$preName}</td>
  </tr>
  <tr>
    <td>Date Of Birth :</td>
    <td>{$dob}</td>
  </tr>
  <tr>
    <td>Gender:</td>
    <td>{$gender}</td>
  </tr>
  <tr>
    <td>Address:</td>
    <td>{$address}</td>
  </tr>
  <tr>
    <td>Suburb:</td>
    <td>{$suburb}</td>
  </tr>
  <tr>
    <td>State:</td>
    <td>{$state}</td>
  </tr>
  <tr>
    <td>Postcode:</td>
    <td>{$postcode}</td>
  </tr>
  <tr>
    <td>Mobile No.:</td>
    <td>{$mobile}</td>
  </tr>
  <tr>
    <td>In Shop</td>
    <td>{$shopCode}</td>
  </tr>
  <tr>
    <td>By Manager</td>
    <td>{$managerName}</td>
  </tr>
</table>

    	";

    self::sendNormalEmail("office2@phonecollection.com.au", "We have New Staff {$preName} On Board", $mailBody);	
    	
    }
    
    public function sendingWelomeLetter($emailAddress,$firstName,$lastName){
    	
    	$strContent = '<body style="font-family:Arial, Helvetica, sans-serif">
<p><img src="http://www.phonecollection.com.au/images/logo_phonecollection.png" alt="logo"  /><br />
  <br />
Dear  [=NAMES=],</p>
<p><strong>No bear  hugs, just a warm welcome!</strong> We are delighted you are joining us as a sales  assistant. Your role is critical in fulfilling the mission of our company.</p>
<p>To avoid  the delay of your first payment, we would like you to provide the enclosed  information and give back to us within 7 days. </p>
<p><strong>Detail from you</strong></p>
<ul>
  <li>New staff information detail</li>
  <li>A copy of Photo ID (passport,  drivers licence, proof of age)</li>
</ul>
<p><strong>Company policy you would need to know (need a  signature on)</strong></p>
<ul>
  <li>Customer service standards</li>
  <li>Company property declaration</li>
  <li>Cash handling declaration</li>
  <li>Break-time policy</li>
</ul>
<p>All related  documents can be found on company Home app à Document à Company policy</p>
<p>Once again,  we’d just like you to know that you, as part of our family, are our most valuable  asset. We could not accomplish what we do every day without our employees. We  are very pleased to welcome you to Phone Collection/ I Collection and look  forward to working with you. </p>
<p>Sincerely, </p>
<p>&nbsp;</p>
<p>Phone  Collection/ I Collection Head office</p>
</body>';
    	
    	$strContent = str_replace("[=NAMES=]",ucwords($firstName." ".$lastName),$strContent);
    	self::sendNormalEmail($emailAddress,'Welcome on Board '.ucwords($firstName." ".$lastName), $strContent);
    	
    }
}
?>