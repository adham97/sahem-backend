<?php
    // $_POST['user_id'] = '1';
    // $_POST['token_id'] = 'aDlLYmpjR21LdSEkMjIvMDUvMjEgMDI6MjY6MDUhJDA1Njg3NjYxMjUhJDE5Mi4xNjguMS4xNTM=';
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
                $message['from_user_id'] = $user->from_user_id;
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
?>