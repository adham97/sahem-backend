<?php
	require_once "config.php";

	function is_login(){
				
		global $conn;

		if (empty($_POST['token_id'])) {		
			$response['result'] = "failed" ;
			$response['code'] = 4;
			$response['alert_message'] = "Invalid Info empty";
			
			die(json_encode($response)); exit;
					
		} else {
			$token_id = check_security($_POST['token_id'], "string");
					
			$decode_token = base64_decode($token_id);
			$d_token = explode( '!$', $decode_token );
			$email_phone = $d_token[2];
			
			$sql_auth = "SELECT * 
						  FROM `auth_table` 
						  WHERE token_id = :token_id
						  AND   email_phone = :email_phone";
			try{
				$stat = $conn->prepare($sql_auth);
				$stat->bindParam(':token_id', $token_id, PDO::PARAM_STR);
				$stat->bindParam(':email_phone', $email_phone, PDO::PARAM_STR);				
				$stat->execute();
				
			}
			catch (PDOException $ex) {	
				$response['result'] = "failed";
				$response['code'] = 2;
				$response['alert_message'] = "not available now11". $ex;
			
				die(json_encode($response)); exit;
			}
			
			$result_auth = $stat->fetch();
			
			if($result_auth && $stat->rowCount() == 1) {
				
				$user['email_phone'] = $result_auth['email_phone'];
				$user['token_id'] = $result_auth['token_id'];
				return $user;
			
			} else {
				$response['result'] = "failed" ;
				$response['code'] = 4;
				$response['alert_message'] = "You are is not registered.";

				die( json_encode($response)); exit;
			}
		}
	}
	
	
	
	
	function get_user_id($email){
		
		global $conn;
		
		$sql_select_userID = "SELECT `user_id` 
								FROM users 
								WHERE `email` = :email";

		try {
			$stmt = $conn->prepare($sql_select_userID);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute(); 

		} catch (PDOException $ex) {  

			$response['result'] = "failed";
			$response['code'] = 2;
			$response['alert_message'] = "error DB: ".$ex;

			die(json_encode($response));exit;
		}

		$user_info = $stmt->fetch();
		
		if( $user_info && $stmt->rowCount() == 1){
			$user_id = $user_info['user_id'];
			
			return $user_id;

		}else{
			$response['result'] = "failed";
			$response['code'] = 666;
			$response['alert_message'] = "Error, Try Again";
		
			die(json_encode($response)); exit;
		}
				
	}
	

?>
 

