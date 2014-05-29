<?php

class Model_EncryptHelper {

	public static function sslPassword($password){
	
		$pwdSource = $password;
		$iv="thisisnormancode";
		$pass="197979norman";
		$method = 'aes-128-cbc';
		if(strlen($pwdSource)>0){
				
			return openssl_encrypt($pwdSource, $method, $pass,false,$iv);
		}
		return false;
	
	}
	
	public static function getSslPassword($data){
	
		$iv="thisisnormancode";
		$pass="197979norman";
		$method = 'aes-128-cbc';
		if(strlen($data)>0){
			return $pwdResult = openssl_decrypt($data, $method, $pass,false,$iv);
		}
		return false;
			
	}
	
	public static function hashsha($str){
	
	/*
	 * md2           32 a9046c73e00331af68917d3804f70655                  
md4           32 866437cb7a794bce2b727acc0362ee27
md5           32 5d41402abc4b2a76b9719d911017c592
sha1          40 aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d
sha256        64 2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e730
sha384        96 59e1748777448c69de6b800d7a33bbfb9ff1b463e44354c3553
sha512       128 9b71d224bd62f3785d96d46ad3ea3d73319bfbc2890caadae2d
crc32          8 3d653119
crc32b         8 3610a686
	 * 
	 * */
		
	return hash("sha256",$str);	
		
	}
	
	public static function convertTo36($num){
		$resArr = array();
		$arrMap = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	
	while($num>=36){
		
		$digit = $num % 36;
		$resArr[] = $arrMap[$digit];
		
		$num = floor($num /36);
	}
	$resArr[] = $arrMap[$num];
	$resArr = array_reverse($resArr);
	
	return implode("",$resArr);
	}
		
}

?>