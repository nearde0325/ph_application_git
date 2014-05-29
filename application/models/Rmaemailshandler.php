<?php
/*
this is the class which send automatic email when:
Something Done:

Something Wrong:
1) If download failed, Email me 
 

*/
class Model_Rmaemailshandler
{
	//protected $receipt = "norman@missionpc.com.au";

	public function rmaRejectedemail($rmaID,$shopemail,$manageremail,$comment){

		date_default_timezone_set('Australia/Melbourne');
		$timenow = date("D Y-m-d g:i a");		
		
		$mailbody = "Hi There,<br /><br />";
		$mailbody .= "I am sorry to info you that the warranty return has been rejected on".$timenow." as follow reason<br />";
		$mailbody .= $comment."<br />"; 
		$mailbody .= "Best Regards,<br /> Flora<br /><br />,Email: flora.hong@phonecollection.com.au<br />";
		
		
		$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
		$mail = new Zend_Mail();
		$mail->setFrom('warehouse1@phonecollection.com.au', 'warehouse1@phonecollection.com.au');
		$mail->setBodyHtml($mailbody);
		
		$mail->addTo(trim($shopemail),trim($shopemail));
		$mail->addTo(trim($manageremail),trim($manageremail));	
		//send email to flora
		$mail->addTo(trim("flora.hong@phonecollection.com.au"),trim("flora.hong@phonecollection.com.au"));
		$mail->setSubject('Your RMA ID: '.$rmaID.' has been rejected');
		 
		// if can not send any mail , there is no point to alarm,
		@$mail->send($transport);		
		
	}
	
	public function faultySendBackEmail($sMethod){
		
		date_default_timezone_set('Australia/Melbourne');
		$timenow = date("D Y-m-d g:i a");

		$mailbody = "Dear Warehouse, we have done the RMA online and will need to post the item back to Supplier Using Following Method : [";
		$mailbody .= $sMethod."]. If you have schedual Pickup of this courier/ eBay Department Delivery, Please Let we Admin department know (elaine).";
		$mailbody .= "Many Thanks. Please make the call for us.";
		$transport = new Zend_Mail_Transport_Smtp('mail.tpg.com.au');
		$mail = new Zend_Mail();
		$mail->setFrom('do-not-reply@phonecollection.com.au', 'do-not-reply@phonecollection.com.au');
		$mail->setBodyHtml($mailbody);
		
		$mail->addTo(trim("jeffrey.zhang@phonecollection.com.au"),trim("jeffrey.zhang@phonecollection.com.au"));
		$mail->addTo(trim("flora.hong@phonecollection.com.au"),trim("flora.hong@phonecollection.com.au"));
		$mail->setSubject('Please Arrange Courier or Delivery for Send the RMA Back to Supplier');
			
		// if can not send any mail , there is no point to alarm,
		@$mail->send($transport);		
		
		
	}
			
    
}
?>