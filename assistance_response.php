<?php
	if(!empty(isset($_POST['token_id'])) && !empty(isset($_POST['assistance_request_id'])) && isset($_POST['option'])) {

		require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

		$assistance_request_id = check_security($_POST['assistance_request_id'], 'string');
        $option = check_security($_POST['option'], 'string');

        switch ($option) {
            case '2':
                updateAssistance($option, $assistance_request_id);
                break;
            case '3':
                updateAssistance($option, $assistance_request_id);
                break;
            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }
	}

    function updateAssistance($acceptance_id, $assistance_request_id) {
        global $conn;
        $sql_update_assistance = "UPDATE assistance_request
                                  SET acceptance_id = :acceptance_id 
                                  WHERE assistance_request_id = :assistance_request_id"; 

        try {
            $stmt_update = $conn->prepare($sql_update_assistance);
            $stmt_update->bindparam(':acceptance_id', $acceptance_id, PDO::PARAM_STR);
            $stmt_update->bindparam(':assistance_request_id', $assistance_request_id, PDO::PARAM_STR);  
            $stmt_update->execute();		
        
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $user_id = check_security($_POST['user_id'], 'string');

        $sql_insert_notifications = "INSERT INTO notifications (body, user_id, title, notification_type_id, status_id) 
        VALUES (:body, :user_id, :title, :notification_type_id, :status_id)"; 
        $body = $assistance_request_id;
        $status_id = 1;
        if($acceptance_id == '2')
            $title = 'Your request has been accepted and sent';
        else 
            $title = 'Your request has been rejected';
        $notification_type_id = 3;
        try {
            $stmt_insert_notifications = $conn->prepare($sql_insert_notifications);
            $stmt_insert_notifications->bindparam(':body', $body, PDO::PARAM_STR);
            $stmt_insert_notifications->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert_notifications->bindparam(':title', $title, PDO::PARAM_STR);
            $stmt_insert_notifications->bindparam(':notification_type_id', $notification_type_id, PDO::PARAM_STR);
            $stmt_insert_notifications->bindparam(':status_id', $status_id, PDO::PARAM_STR);
            $stmt_insert_notifications->execute();		
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = "Acceptance Done";

        die(json_encode($response));exit;
    }
?>	