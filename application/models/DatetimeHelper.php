<?php

//namespace application\models;

/**
 *
 * @author norman
 *  This Date Time Helper is Designed By Norman For his Own Use/ Convenience
 *  This Code was construced  on his own time 
 *  Copied here for his convenience use only.
 *  The IP of this Date Time Helper belongs to Norman ONLY
 *  it will only create php  Dafault date format  YYYY-MM-dd      
 */
class Model_DatetimeHelper {
	
	/**
	 * This is no use if you do not new an instance
	 */
	function __construct() {
		date_default_timezone_set('Australia/Melbourne');		
	}
/**
 *  get Date Today
 * @param string $cotr Default connector is -  2000-01-01
 * @return string
 */
	public static function dateToday($cotr = "-"){
	
		date_default_timezone_set('Australia/Melbourne');
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		return	date($dateString);
		
	}
	public static function dayToday(){
		
		date_default_timezone_set('Australia/Melbourne');
		return date("N");
	}
	public static function dayLeft(){
		date_default_timezone_set('Australia/Melbourne');
		return  8 - (int)date("N");
	}
	/**
	 * get time now 
	 * @param string $cotr default connector is : ,23:59:59
	 * 
	 */
	public static  function timeNow($cotr = ":"){
		date_default_timezone_set('Australia/Melbourne');
		
		$timeString = "H" . $cotr . "i" . $cotr . "s";
		return  date($timeString);
	}
	/**
	 * get yesterday date
	 * @param unknown_type $cotr
	 */
	public static function dateYesterday($cotr = "-"){
		date_default_timezone_set('Australia/Melbourne');
		$dateToday = self::dateToday($cotr);
		$dateYesterday = self::adjustDays("sub", $dateToday,1,$cotr);

		return $dateYesterday;
	}
	/**
	 * get this week monday  
	 * @param string $cotr
	 */
	
	public static  function getThisWeekMonday($cotr = "-"){

		date_default_timezone_set('Australia/Melbourne');

		$dateString = "Y" . $cotr . "m" . $cotr . "d";
			
		if(intval(date("w"))==1){
			$thisMonday = date($dateString,strtotime("this monday"));
		}
		else{
			$thisMonday = date($dateString,strtotime("last monday"));
		}		
		
		return $thisMonday; 
		
	}
	/**
	 *  This Week Sunday  
	 * @param string $cotr
	 */
	public static  function getThisWeekSunday($cotr = "-"){
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		$thisMonday = self::getThisWeekMonday();
		$thisSunday = date($dateString,strtotime("+6 day",strtotime($thisMonday)));
		
		return $thisSunday;
	}
	/**
	 *  Last week Monday
	 * @param string $cotr
	 */
	public static  function getLastWeekMonday($cotr = "-"){

		$thisMonday = self::getThisWeekMonday();		
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		$lastMonday = date($dateString,strtotime("-7 day",strtotime($thisMonday)));	
		
		return $lastMonday;
	}
	/**
	 * last week Sunday
	 * @param string $cotr
	 */
	public static  function getLastWeekSunday($cotr = "-"){
		
		$thisMonday = self::getThisWeekMonday();
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		$lastSunday = date($dateString,strtotime("-1 day",strtotime($thisMonday)));
		
		return $lastSunday;
	}	
	/**
	 *  Adjust Days
	 * @param string $adjDir  Adjust Direction add / sub
	 * @param string $date  begin ajust date
	 * @param int $num   how many
	 * @param string $cotr connector
	 */	
	public static  function adjustDays($adjDir,$date,$num,$cotr = "-"){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateResult = new DateTime($date);
		if($adjDir == "add"){
			$dateResult->add(new DateInterval('P' . $num . 'D'));		
		}
		elseif($adjDir == "sub"){
			
			$dateResult->sub(new DateInterval('P' . $num . 'D'));
		}
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		return $dateResult->format($dateString);
		
	}
	/**
	 *  Adjust Weeks
	 * @param string $adjDir  Adjust Direction add / sub
	 * @param string $date  begin ajust date
	 * @param int $num   how many
	 * @param string $cotr connector
	 */	
	public static  function adjustWeeks($adjDir,$date,$num,$cotr = "-"){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateResult = new DateTime($date);
		if($adjDir == "add"){
			$dateResult->add(new DateInterval('P' . $num*7 . 'D'));
		}
		elseif($adjDir == "sub"){
				
			$dateResult->sub(new DateInterval('P' . $num*7 . 'D'));
		}
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		return $dateResult->format($dateString);		
		
	}
	/**
	 *  Adjust Month
	 * @param string $adjDir  Adjust Direction add / sub
	 * @param string $date  begin ajust date
	 * @param int $num   how many
	 * @param string $cotr connector
	 */	
	public static  function adjustMonths($adjDir,$date,$num,$cotr = "-"){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateResult = new DateTime($date);
		if($adjDir == "add"){
			$dateResult->add(new DateInterval('P' . $num . 'M'));
		}
		elseif($adjDir == "sub"){
				
			$dateResult->sub(new DateInterval('P' . $num . 'M'));
		}
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		return $dateResult->format($dateString);
		
	}
	/**
	 *  Adjust Years
	 * @param string $adjDir  Adjust Direction add / sub
	 * @param string $date  begin ajust date
	 * @param int $num   how many
	 * @param string $cotr connector
	 */	
	public static  function adjustYears($adjDir,$date,$num,$cotr = "-"){
		
		date_default_timezone_set('Australia/Melbourne');
		
		$dateResult = new DateTime($date);
		if($adjDir == "add"){
			$dateResult->add(new DateInterval('P' . $num . 'Y'));
		}
		elseif($adjDir == "sub"){
				
			$dateResult->sub(new DateInterval('P' . $num . 'Y'));
		}
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		return $dateResult->format($dateString);
		
	}
	/**
	 *  Create Data Array list 
	 * @param string $dateBegin
	 * @param string $dateEnd
	 * @param string $cotr  connector
	 * @return  array   array of date List 
	 */
	public static  function createDateArray($dateBegin,$dateEnd,$cotr ="-"){
		
		date_default_timezone_set('Australia/Melbourne');
		$dateArray = array();
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		$dateArray[]=date($dateString,strtotime($dateBegin));
		$dateResult = new DateTime($dateBegin);
		$dateComp = new DateTime($dateEnd);
		
		while($dateResult<$dateComp){
			$dateBegin = self::adjustDays("add", $dateBegin,1,$cotr);
			$dateArray[] = $dateBegin;
			$dateBegin = str_replace($cotr,"-",$dateBegin);
			$dateResult = new DateTime($dateBegin);			
		}
		
		return $dateArray;
		
	}
	public static function tranferToInt($date){
		date_default_timezone_set('Australia/Melbourne');
		return date("U",strtotime($date));
		
	}
	public static function tranferToIntAde($date){
		date_default_timezone_set('Australia/Adelaide');
		return date("U",strtotime($date));		
	}
	public static function getMondayByDate($date,$cotr = "-"){
		date_default_timezone_set('Australia/Adelaide');
		
		$dateString = "Y" . $cotr . "m" . $cotr . "d";
		
		$day = date("N",strtotime($date));
		$dayDiff = $day - 1;
		
		if( $dayDiff>0 ){
			$date = self::adjustDays("sub", $date,$dayDiff);
		}
		
		return date($dateString,strtotime($date));
		
	}
	public static function getFirstDayOfTheMonth($date,$cotr = "-"){
		$dateString = "Y" . $cotr . "m" . $cotr . "01";
		date_default_timezone_set('Australia/Melbourne');
		
		return date($dateString,strtotime($date));	
	}
	
	public static function getLastDayOfTheMonth($date,$cotr = "-"){
		
                $dateString = "Y" . $cotr . "m" . $cotr . "t";
                return date($dateString,strtotime($date));
		
		
	}
	public static function dateDiff($date1,$date2){
		
		$intDate1 = self::tranferToInt($date1);
		$intDate2 = self::tranferToInt($date2);
		return (int)abs(($intDate1 - $intDate2)/(3600*24));
		
	}
	
	
	
}

?>