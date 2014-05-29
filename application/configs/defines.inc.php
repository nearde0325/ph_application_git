<?php
/**
 * Define all the pre defined parameters
*/

/*temp file upload folder*/

define('TMP_FILE_FOLDER', "/tmp");
define('SALES_LINE_FOLDER',"/import/sl");
define('STOCK_FOLDER',"/import/sk");
define('SALES_MAN_FOLDER',"/import/sm");


/* Order Status*/
define('ORDER_STATUS_CANCEL',100);
define('ORDER_STATUS_FINALLIZED',10);

/*Attendance Status*/
define('START_EARLY',1);
define('START_LATE',2);
define('FINISH_EARLY',5);
define('FINISH_LATE',8);
define('START_NO_RECORD',20);
define('FINISH_NO_RECORD',30);
define('START_REALLY_EARLY',11);
define('FINISH_REALLY_LATE',18);
/*Break Time*/
define('BREAK_15_MIN',1);
define('BREAK_30_MIN',2);
define('BREAK_45_MIN',3);
define('BREAK_60_MIN',4);
define('BREAK_NO_BREAK',9);
define('ARR_MANAGER',serialize(array(
				1=> array("WHPC","BBPC","FGIC","CBPC","BSPC","BSIC","BSXP","KCPC","CSIC","DCIC","PMPC","SLIC","PMIC","EPPC","EPIC","NLPC","HPIC","BHPC","ADPC","CLPC","WLIC","CLIC","WBPC","WBIC","WGIC","WGPC","HPPC"),//Norman
				110 => array("WHPC","BBPC","FGIC","CBPC","BSPC","BSIC","BSXP","KCPC","CSIC","DCIC","PMPC","SLIC","PMIC","EPPC","EPIC","NLPC","HPIC","BHPC","ADPC","CLPC","WLIC","CLIC","WBPC","WBIC","WGIC","WGPC","HPPC"),//Admin
				2 => array("CBPC","PMPC","PMIC","SLIC"),//Hope
				3 => array("FGIC"),//Adele
				4 => array("WHPC","BBPC","FGIC","CBPC","BSPC","BSIC","BSXP","KCPC","CSIC","DCIC","PMPC","SLIC","PMIC","EPPC","EPIC","NLPC","HPIC","BHPC","ADPC","CLPC","WLIC","CLIC","WBPC","WBIC","WGIC","WGPC","HPPC"),//Simon
				27 => array("BHPC","KCPC","DCIC"),//Terra
				115 => array("WGPC","WGIC"),//Iris
				131 => array("ADPC","CLPC","WLIC","CLIC","EPPC","NLPC","EPIC"),//Jason
				156 =>array("ADPC","CLPC","WLIC","CLIC"),//kevin
				165 => array("BSPC","BSIC","BSXP"),// will
				162 => array("HPIC","BBPC"),//kelly
				36 => array("NLPC"),// chow
				125 => array("WBIC","WBPC"),//chtherine
				152 =>array("HPIC"),// alice
				150 =>array("SLIC"),//jessie
				181 =>array("CLPC","CLIC"),//bing
				204 =>array("KCPC","DCIC","BHPC")//john
				)));
define('ARR_APOS_SHOP_MAPPING',serialize( array(
			"ADPC" =>"AD",
			"BBPC" =>"BB",
			"BHPC" =>"BH",
			"BSPC" =>"BS",
			"BSIC" =>"BSIC",
			"BSXP" =>"BSXP",
			"CBPC" =>"CB",
			"CLPC" =>"CL",
			"CLIC" =>"CLIC",
			"CSIC" =>"CSIC",
			"DCIC" =>"DCIC",
			"EPIC" =>"EP",
			"FGIC" =>"FGIC",
			"HPIC" =>"HPIC",
			"KCPC" =>"KC",
			"NLPC" =>"NL",
			"PMPC" =>"PM",
			"PMIC" =>"PMIC",
			"SLIC" =>"SLIC",
			"WBPC" =>"WB",
			"WBIC" =>"WBIC",
			"WGPC" =>"WGPC",
			"WGIC" =>"WGIC",
			"WLIC" =>"WLIC",
		    "EPPC" =>"EPPC",
	)));
define('ARR_RCENTER_MAPPING',serialize( array(
			"ADPC" => 17,
			"BBPC" => 18,
			"BHPC" => 4,
			"BSIC" => 21,
			"BSPC" => 1,
			"BSXP" => 1,
			"CBPC" => 6,
			"CLIC" => 19,
			"CLPC" => 19,
			"CSIC" => 2,
			"DCIC" => 11,
			"EPPC" => 8,
			"EPIC" => 8,
			"FGIC" => 14,
			"HPIC" => 5,
			"KCPC" => 12,
			"NLPC" => 10,
			"PMIC" => 15,
			"PMPC" => 99,
			"SLIC" => 13,
			"WBIC" => 9,
			"WBPC" => 9,
			"WGIC" => 16,
			"WGPC" => 16,
			"WLIC" => 7,
			"WHLU" => 20
)));
define('ARR_RCENTER_REV_MAPPING',serialize( array(
			1 => "BSXP",
			2 => "CSIC",
			4 => "BHPC",
			5 => "HPIC",
			6 => "CBPC",
			7 => "WLIC",
			8 => "EPPC",
			9 => "WBPC",
			10 => "NLPC",
			11 => "DCIC",
			12 => "KCPC",
			13 => "SLIC",
			14 => "FGIC",
			15 => "PMIC",
			16 => "WGIC",
			17 => "ADPC",
			18 => "BBPC",
			19 => "CLPC",
			20 => "WHLU",
			21 => "BSIC"
)));
