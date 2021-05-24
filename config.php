<?php

	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "sahem"; 

	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

	try{

		$conn = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8", $dbuser, $dbpass, $options);

	} catch(Exeption $ex) {

		die("Failed to connect DataBase : ".$ex -> getMessage());
	}
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	function CloseConnection($conn){
		$conn->close();
	}

	// Functions for Encrypt & Decrypt
	function encrypt($plaintext) {
		$CIPHER = "aes-128-cbc";
		$KEY = 'r5u8xAA/D(G-KaPdSVV$Yp336v9y)B$E8$48uFF*%)d5rG^Hsaw';
		$IV = 'km)nm(z{s}#$23xz';
		
		if (in_array($CIPHER, openssl_get_cipher_methods()))
		{
			$ciphertext = openssl_encrypt($plaintext, $CIPHER, $KEY, $options=0, $IV);
			return $ciphertext;
		}
	}

	function decrypt($ciphertext) {
		$CIPHER = "aes-128-cbc";
		$KEY = 'r5u8xAA/D(G-KaPdSVV$Yp336v9y)B$E8$48uFF*%)d5rG^Hsaw';
		$IV = 'km)nm(z{s}#$23xz';

		if (in_array($CIPHER, openssl_get_cipher_methods()))
		{			
			$plaintext = openssl_decrypt($ciphertext, $CIPHER, $KEY, $options=0, $IV);			
			return $plaintext;
		}
	}

	// Function to get the client IP address   (Better)
	function get_client_ip() {
		$ipaddress = '';
				
		// check for shared internet/ISP IP
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		
		// check for IPs passing through proxies
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			// check if multiple ips exist in var
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
				$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				foreach ($iplist as $ip)
					$ipaddress = $ip;
			} 
			else
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		
		else if(isset($_SERVER['HTTP_X_FORWARDED']) )
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) )
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];

		else if(isset($_SERVER['HTTP_FORWARDED_FOR']) )
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		
		else if(isset($_SERVER['HTTP_FORWARDED']) )
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		
		// return unreliable ip since all else failed
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
			
		return $ipaddress;
	}

	// function return secure varible 
	function check_security($variable, $type){
		$length_chk_array = 8;
	
		$variable = trim($variable);
		$variable = htmlspecialchars($variable);
		$variable = stripcslashes($variable);
	  
	  
		if($type == 'string'){
			$chk_array = array("'", ")", "(", "]", "[", "--", "#", ";", "!"); 
			
		} elseif ($type == 'password'){
			$chk_array = array("'", ")", "(", "]", "[", "--", ";",); 
			$length_chk_array = 6;
			
			//password	
			if(strlen(trim($variable)) < 6){
				$response['result'] = "failed";
				$response['code'] = 4;
				$response['alert_message'] = "Try again, Password must have at least 6 characters.";
				
				die(json_encode($response));exit;
			}
			
		}elseif ($type == 'number'){
			if(!is_numeric($variable)){
				$response['result'] = "failed";
				$response['code'] = 4;
				$response['alert_message'] = "Try again";

				die(json_encode($response));exit;
			} else
				return $variable;
		}
		
		for($i=0; $i<=$length_chk_array; $i++){
			$variable = str_ireplace($chk_array[$i] , "" , $variable);
		}
	  
		$variable = strip_tags($variable);   // same to xss_clean
	
		return $variable;
	}	

?>