<?php
    // $_POST['user_id'] = '1';
    // $_POST['token_id'] = 'aEpUazdjOHNIOCEkMDgvMDYvMjEgMDQ6MTM6MjAhJGRpbWFAZ21haWwuY29tISQxOTIuMTY4LjEuMTUz';
    // $_POST['is_from_sender'] = '0'; 
    // $_POST['content'] = 'Hi'; 
    // $_POST['message_type'] = '1';

    // $_POST['header_id'] = '1';

    if (empty(isset($_POST['token_id']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        
        $sql_select_users = "SELECT * FROM  header";

        try {
            $stmt_users = $conn->prepare($sql_select_users);
            $stmt_users->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $users_info = $stmt_users->fetchAll(PDO::FETCH_OBJ);
        $users = array();

        if($users_info && $stmt_users->rowCount() >= 1){
            require_once 'select_message.php';
        
            foreach($users_info as $user){
                $message = selectLastMessage($user->header_id);
                $message['user'] = getUser($user->from_user_id);
                
                array_push($users, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $users;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there Message.";

            die(json_encode($response));exit;
        }
    }
    
    function getUser($user_id) {
        global $conn;    

        $sql_select_user = "SELECT * FROM  users
                            WHERE user_id = :user_id";

        try {
            $stmt_user = $conn->prepare($sql_select_user);
            $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_STR);
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
            $message['online'] = $user_info['online'];

            return $message;

        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }
?>