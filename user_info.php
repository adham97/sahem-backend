<?php
    if (empty(isset($_POST['user_id'])) && empty(isset($_POST['token_id']))) {		
		$response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $user_id = check_security($_POST['user_id'], 'string');

        $sql_select_user = "SELECT * FROM  users
                            WHERE user_id = :user_id";

        try {
            $stmt_user = $conn->prepare($sql_select_user);
            $stmt_user->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_user->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;

        }

        $user_info = $stmt_user->fetch();

        if($user_info && $stmt_user->rowCount() == 1){
            $message['user_id'] = $user_info['user_id'];				
            $message['first_name'] = $user_info['first_name'];
            $message['father_name'] = $user_info['father_name'];
            $message['grandfather_name'] = $user_info['grandfather_name'];
            $message['last_name'] = $user_info['last_name'];
            $message['email'] = $user_info['email'];
            $message['identify_id'] = $user_info['identify_id'];
            $message['phone'] = $user_info['phone'];
            $message['city'] = $user_info['city'];
            $message['street'] = $user_info['street'];
            $message['user_role'] = $user_info['user_role'];
            $message['image'] = $user_info['image_path'];
            $message['id_photo'] = $user_info['id_photo'];
            $message['token_id'] = $_POST['token_id'];

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $message;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }
?>