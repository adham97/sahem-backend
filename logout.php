<?php
	require_once "config.php";
	require_once "auth_login.php";

	$user = is_login(); 

	if( !empty($user['token_id']) && !empty($user['email_phone']) ) {		

		$token_id = check_security($user['token_id'], "string");
		$email_phone = check_security($user['email_phone'], "string");

		$sql_delete = "DELETE FROM `auth_table` 
					   WHERE token_id = :token_id
					   AND   email_phone = :email_phone";
			
			try {
				$stat_delete = $conn->prepare($sql_delete);
				$stat_delete->bindParam(':token_id', $token_id, PDO::PARAM_STR);
				$stat_delete->bindParam(':email_phone', $email_phone, PDO::PARAM_STR);
				$stat_delete->execute();
				
			} catch (PDOException $ex) {	
				$response['result'] = "failed";
				$response['code'] = 2;
				$response['alert_message'] = "not available now11". $ex;
			
				die(json_encode($response)); exit;
			}

			$sql_update_online = "UPDATE users SET online = :online 
								  WHERE email = :email
								  OR    phone = :phone";
				
			$online = '0';
			echo $email_phone;
			try{
				$insert_online = $conn->prepare($sql_update_online);
				$insert_online->bindParam(':online', $online, PDO::PARAM_STR);
				$insert_online->bindParam(':email', $email_phone, PDO::PARAM_STR);
				$insert_online->bindParam(':phone', $email_phone, PDO::PARAM_STR);
				$insert_online->execute();

			}catch (PDOException $ex) {	

				$response['result'] = "failed";
				$response['code'] = 3;
				$response['alert_message'] = "error DB: ".$ex;

				die(json_encode($response));exit;
			}
			
			$response["result"] = "success";
			$response['code'] = 3;
			$response["message"] = "Done logout";

			die(json_encode($response)); exit;	
	}
?>