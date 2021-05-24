<?php

	// $_POST['first_name'] = "Dima";
	// $_POST['father_name'] = "Saed";
	// $_POST['grandfather_name'] = "Abd Al-Fatah";
	// $_POST['last_name'] = "Asqalan";
	// $_POST['email'] = "dima96@gmail.com";
	// $_POST['password'] = "dima123";
	// $_POST['identify_id'] = "423957509";
	// $_POST['phone'] = "056873425";
	// $_POST['city'] = "Nablus";
	// $_POST['street'] = "Al-Etehad Street";
	// //$_POST['user_role'] = "1";
	

	if( isset($_POST['first_name']) && isset($_POST['father_name']) && isset($_POST['grandfather_name']) && 
	    isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password']) && 
	    isset($_POST['identify_id']) && isset($_POST['phone']) && isset($_POST['city']) && 
	    isset($_POST['street']) ) {

		require_once 'config.php';
		$user = check_user();

		if($user['message'] == 'not exist'){

		    // check security variables
		    $first_name = check_security($_POST['first_name'], 'string');
			$father_name = check_security($_POST['father_name'], 'string');
			$grandfather_name = check_security($_POST['grandfather_name'], 'string');
		    $last_name = check_security($_POST['last_name'], 'string');
		    $email = check_security($_POST['email'], 'string');		
		    $password = check_security($_POST['password'], 'password');
		    $identify_id = check_security($_POST['identify_id'], 'string'); 
		    $phone = check_security($_POST['phone'], 'string');
		    $city = check_security($_POST['city'], 'string');
		    $street = check_security($_POST['street'], 'string');	
			$user_role = 3;
			$image_path = 'images/user_image/unknown.png';

			if( !empty($first_name) && !empty($father_name) && !empty($grandfather_name) && !empty($last_name) && 
	    		!empty($email) && !empty($password) && !empty($identify_id) && !empty($phone) && !empty($city) && 
	    		!empty($street) && !empty($user_role)) {

				// encrypt passwprd
		    	$password = encrypt($password);
				
				// Insert To users table For Registere
		    	$sql_insert = "INSERT INTO `users`(`first_name`, `father_name`, `grandfather_name`, `last_name`, `email`, `password`, 
		    	`identify_id`, `phone`, `city`, `street`, `user_role`, `image_path`, `id_photo`, `created_at`) VALUES (:first_name, :father_name, :grandfather_name, 
		    	:last_name, :email, :password, :identify_id, :phone, :city, :street, :user_role, :image_path, :id_photo, :created_at)";

				$created_at = $date = date('Y-m-d H:i:s'); 
				
				try {
					$stmt_register = $conn->prepare($sql_insert);
					$stmt_register->bindparam(':first_name', $first_name, PDO::PARAM_STR);  
					$stmt_register->bindparam(':father_name', $father_name, PDO::PARAM_STR);  
					$stmt_register->bindparam(':grandfather_name', $grandfather_name, PDO::PARAM_STR);  
					$stmt_register->bindparam(':last_name', $last_name, PDO::PARAM_STR);
					$stmt_register->bindparam(':email', $email, PDO::PARAM_STR);
					$stmt_register->bindparam(':password', $password, PDO::PARAM_STR); 
					$stmt_register->bindparam(':identify_id', $identify_id, PDO::PARAM_STR);  
					$stmt_register->bindparam(':phone', $phone, PDO::PARAM_STR);
					$stmt_register->bindparam(':city', $city, PDO::PARAM_STR); 
					$stmt_register->bindparam(':street', $street, PDO::PARAM_STR);  
					$stmt_register->bindparam(':user_role', $user_role, PDO::PARAM_STR); 
					$stmt_register->bindparam(':image_path', $image_path, PDO::PARAM_STR);
					$stmt_register->bindparam(':id_photo', $image_path, PDO::PARAM_STR);
					$stmt_register->bindparam(':created_at', $created_at, PDO::PARAM_STR);  
					$stmt_register->execute();		
					
				}  catch (PDOException $ex) {	
					
					$response['result'] = "failed";
					$response['code'] = 3;
					$response['alert_message'] = "Try Agin, error DB: ".$ex;
				
					die(json_encode($response));exit;
				}

				$_POST['email_phone'] = $email;
				require_once "login.php";

				$response['result'] = "success";
				$response['code'] = 1;
				$response['message'] = "Register Done";

				die(json_encode($response));exit;

			} else {

				$response['result'] = "failed";
				$response['code'] = 0;
				$response['alert_message'] = "Fields Require";

				die(json_encode($response));exit;
			}

		} else {

			$response['result'] = "failed";
			$response['code'] = 5;
			$response['alert_message'] = "This user already register";

			die(json_encode($response));exit;
		}

	} else {

		$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
	}

	function check_user(){
		global $conn;
		
		$phone = check_security($_POST['phone'], 'string');
	    $email = check_security($_POST['email'], 'string');	

		$sql_select_user = "SELECT * FROM users WHERE email = :email OR phone = :phone";
	
		try{

			$user = $conn->prepare($sql_select_user);
			$user->bindparam(':email', $email, PDO::PARAM_STR);
			$user->bindparam(':phone', $phone, PDO::PARAM_STR);
			$user->execute();
	
		} catch(PDOException $ex){

			$response['result'] = "failed";
			$response['code'] = 3;
			$response['alert_message'] = "Try Agin, error DB: ".$ex;
		
			die(json_encode($response));exit;
		}

		$user_info = $user->fetch();
		$message = '';
		if($user_info && $user->rowCount() == 1)
			$message = 'exist';
		else 
			$message = 'not exist';
	
		$response['result'] = "success";
		$response['code'] = 1;
		$response['message'] = $message;

		return $response;
	}
?>	