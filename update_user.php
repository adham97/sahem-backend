<?php
	// $_POST['user_id'] = '1';
	// $_POST['first_name'] = "Dima";
	// $_POST['father_name'] = "Saed";
	// $_POST['grandfather_name'] = "Abd Al-Fatah";
	// $_POST['last_name'] = "Asqalan";
	// $_POST['email'] = "dima9@gmail.com";
	// //$_POST['password'] = "dima123";
	// $_POST['identify_id'] = "403957509";
	// $_POST['phone'] = "0568733125";
	// $_POST['city'] = "Nablus";
	// $_POST['street'] = "Al-Etehad Street";
	// $_POST['user_role'] = "1";
	// $_POST['token_id'] = 'WGEwUjNFWE9FMyEkMjYvMDQvMjEgMDU6MDQ6MzchJDA1Njg3NjYxMjUhJDE5Mi4xNjguMS4xNTg=';

	if( isset($_POST['user_id']) && isset($_POST['first_name']) && isset($_POST['father_name']) && 
        isset($_POST['grandfather_name']) && isset($_POST['last_name']) && isset($_POST['email']) && 
        isset($_POST['identify_id']) && isset($_POST['phone']) && isset($_POST['city']) && 
        isset($_POST['street']) && isset($_POST['user_role']) && isset($_POST['token_id']) ) {

		require_once 'config.php';
		require_once 'auth_login.php';
		$user = is_login();

		// check security variables
		$user_id = check_security($_POST['user_id'], 'string');
		$first_name = check_security($_POST['first_name'], 'string');
		$father_name = check_security($_POST['father_name'], 'string');
		$grandfather_name = check_security($_POST['grandfather_name'], 'string');
		$last_name = check_security($_POST['last_name'], 'string');
		$email = check_security($_POST['email'], 'string');		
		$identify_id = check_security($_POST['identify_id'], 'string'); 
		$phone = check_security($_POST['phone'], 'string');
		$city = check_security($_POST['city'], 'string');
		$street = check_security($_POST['street'], 'string');	
		$user_role = check_security($_POST['user_role'], 'string');
		$token = check_security($_POST['token_id'], 'string');

		if( !empty($user_id) && !empty($first_name) && !empty($father_name) && !empty($grandfather_name) && 
			!empty($last_name) && !empty($email) &&  !empty($identify_id) && !empty($phone) && !empty($city) && 
			!empty($street) && !empty($user_role)) {

			// Insert To users table For Registere
			$sql_update = "UPDATE `users` SET `first_name`=:first_name,`father_name`=:father_name, `grandfather_name`=:grandfather_name,`last_name`=:last_name,`email`=:email,`identify_id`=:identify_id,`phone`=:phone,
			`city`=:city,`street`=:street,`user_role`=:user_role,`updated_at`=:updated_at WHERE `user_id`= :user_id"; 

			$updated_at = $date = date('Y-m-d H:i:s'); 
			
			try {
				$stmt_update = $conn->prepare($sql_update);
				$stmt_update->bindparam(':user_id', $user_id, PDO::PARAM_STR);  
				$stmt_update->bindparam(':first_name', $first_name, PDO::PARAM_STR);  
				$stmt_update->bindparam(':father_name', $father_name, PDO::PARAM_STR);  
				$stmt_update->bindparam(':grandfather_name', $grandfather_name, PDO::PARAM_STR);  
				$stmt_update->bindparam(':last_name', $last_name, PDO::PARAM_STR);
				$stmt_update->bindparam(':email', $email, PDO::PARAM_STR);
				$stmt_update->bindparam(':identify_id', $identify_id, PDO::PARAM_STR);  
				$stmt_update->bindparam(':phone', $phone, PDO::PARAM_STR);
				$stmt_update->bindparam(':city', $city, PDO::PARAM_STR); 
				$stmt_update->bindparam(':street', $street, PDO::PARAM_STR);  
				$stmt_update->bindparam(':user_role', $user_role, PDO::PARAM_STR); 
				$stmt_update->bindparam(':updated_at', $updated_at, PDO::PARAM_STR);  
				$stmt_update->execute();		
				
			}  catch (PDOException $ex) {	
				
				$response['result'] = "failed";
				$response['code'] = 3;
				$response['alert_message'] = "Try Agin, error DB: ".$ex;
			
				die(json_encode($response));exit;
			}

			require_once 'user_info.php';
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