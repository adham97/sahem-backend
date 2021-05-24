<?php

	// $_POST['email_phone'] = "0568766125";
	// $_POST['password'] = "dima123";

    require_once 'config.php';  

    if( (isset($_POST['email_phone'])) && isset($_POST['password']) ){
    	$email_phone = check_security($_POST['email_phone'], 'string');
		$password = check_security($_POST['password'], 'password');
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		$ip_address = get_client_ip();

		if( !empty($email_phone) && !empty($password) && !empty($user_agent) && !empty($ip_address)){

			$sql_select_auth = "SELECT * FROM  users
								WHERE email = :email
								OR    phone = :phone";

			try {
				$auth = $conn->prepare($sql_select_auth);
				$auth->bindparam(':email', $email_phone, PDO::PARAM_STR);
			  	$auth->bindparam(':phone', $email_phone, PDO::PARAM_STR);
			  	$auth->execute(); 
		 
			} catch (PDOException $ex) {  
			  
			  $response['result'] = "failed";
			  $response['code'] = 3;
			  $response['alert_message'] = "error DB: ".$ex;
			
			  die(json_encode($response));exit;
			}

			$auth_info = $auth->fetch();

			if($auth_info && $auth->rowCount() == 1){
				$password_decrypt = decrypt($auth_info['password']);

				if($password == $password_decrypt) {

					//**** generate token  ****//
					//** START random number **//

	                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	                $charactersLength = strlen($characters);
	                $randomString = '';

	                for ($i = 0; $i < 10; $i++) {
	                    $randomString .= $characters[rand(0, $charactersLength - 1)];
	                }

					$token = base64_encode($randomString.'!$'.date('d/m/y h:i:s').'!$'.$email_phone.'!$'.$ip_address);

					//**** END generate token ****//
			
					$sql_insert_auth = "INSERT INTO `auth_table`(`token_id`, `email_phone`, `ip_address`, `created_at`, `user_agent`) 
										VALUES (:token_id, :email_phone, :ip_address, :created_at, :user_agent)";
				
					$created_at = date('Y-m-d H:i:s'); 

					try{
						$insert_auth = $conn->prepare($sql_insert_auth);
						$insert_auth->bindParam(':token_id', $token, PDO::PARAM_STR);
						$insert_auth->bindParam(':email_phone', $email_phone, PDO::PARAM_STR);
						$insert_auth->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
						$insert_auth->bindparam(':created_at', $created_at, PDO::PARAM_STR); 
						$insert_auth->bindParam(':user_agent', $user_agent, PDO::PARAM_STR);
						$insert_auth->execute();

					}catch (PDOException $ex) {	

						$response['result'] = "failed";
						$response['code'] = 3;
						$response['alert_message'] = "error DB: ".$ex;

						die(json_encode($response));exit;
					}

					$message['user_id'] = $auth_info['user_id'];				
					$message['first_name'] = $auth_info['first_name'];
					$message['father_name'] = $auth_info['father_name'];
					$message['grandfather_name'] = $auth_info['grandfather_name'];
					$message['last_name'] = $auth_info['last_name'];
					$message['email'] = $auth_info['email'];
					$message['identify_id'] = $auth_info['identify_id'];
					$message['phone'] = $auth_info['phone'];
					$message['city'] = $auth_info['city'];
					$message['street'] = $auth_info['street'];
					$message['user_role'] = $auth_info['user_role'];
					$message['image'] = $auth_info['image_path'];
					$message['id_photo'] = $auth_info['id_photo'];
					$message['online'] = $auth_info['online'];
					$message['token_id'] = $token;
		
					$response['result'] = "success";
					$response['code'] = 1;
					$response['message'] = $message;

					die(json_encode($response));exit;

				} else {
					$response['result'] = "failed";
					$response['code'] = 5;
					$response['alert_message'] = "Wrong in Email or Password, Please Try Agin";

					die(json_encode($response));exit;
				}

			} else {
				$response['result'] = "failed";
				$response['code'] = 4;
				$response['alert_message'] = "Wrong in Email or Password, Please Try Agin";

				die(json_encode($response));exit;
			}

		} else {

			$response['result'] = "failed";
			$response['code'] = 0;
			$response['alert_message'] = "Fields Require";

			die(json_encode($response));exit;
		}

    } else {
    	$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
    }
?>