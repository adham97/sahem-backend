<?php
    // $_POST['notifications_id'] = '1';
    // $_POST['token_id'] = 'cm1hZmZKRlkzTiEkMjgvMDUvMjEgMDE6MDE6NDghJGFobWFkNTZAZ21haWwuY29tISQxOTIuMTY4LjEuMTUz';
    // $_POST['is_from_sender'] = '0'; 
    // $_POST['content'] = 'Hi'; 
    // $_POST['message_type'] = '1';
    // $_POST['type'] = '2';
    // $_POST['number']= '1';
    if (empty(isset($_POST['token_id']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        if(!empty(isset($_POST['notifications_id']))){
            $notifications_id = check_security($_POST['notifications_id'], 'string');

            $sql_update_notification = "UPDATE notifications 
                                        SET status_id = 0
                                        WHERE notifications_id = :notifications_id";

            try {
                $stmt_status = $conn->prepare($sql_update_notification);
                $stmt_status->bindParam(':notifications_id', $notifications_id, PDO::PARAM_STR);
                $stmt_status->execute();

            } catch (PDOException $ex) {	

                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "error DB: ".$ex;

                die(json_encode($response));exit;
            }

        } else if(!empty(isset($_POST['number']))){
            $sql_select_number = "SELECT * FROM notifications WHERE status_id = 1";

            try {
                $stmt_number = $conn->prepare($sql_select_number);
                $stmt_number->execute();

            } catch (PDOException $ex) {	

                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "error DB: ".$ex;

                die(json_encode($response));exit;
            }

            $number_info = $stmt_number->fetchAll(PDO::FETCH_OBJ);

            if($number_info && $stmt_number->rowCount() >= 1){

                $response['result'] = "success";
                $response['code'] = 1;
                $response['message'] = count($number_info);

                die(json_encode($response));exit;

            } else {
                $response['result'] = "failed";
                $response['code'] = 4;
                $response['alert_message'] = "Not there notification.";

                die(json_encode($response));exit;
            }

        } else {

            $sql_select_notification = "SELECT * FROM notifications 
                                        WHERE notification_type_id = 1 
                                        OR    notification_type_id = 2";

            try {
                $stmt_notification = $conn->prepare($sql_select_notification);
                $stmt_notification->execute();

            } catch (PDOException $ex) {	

                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "error DB: ".$ex;

                die(json_encode($response));exit;
            }

            $notification_info = $stmt_notification->fetchAll(PDO::FETCH_OBJ);
            $notifications = array();

            if($notification_info && $stmt_notification->rowCount() >= 1){
                foreach($notification_info as $notification) {
                    switch ($notification->notification_type_id) {
                        case '1':
                        $body = selectAssistance($notification->body);
                        break;

                    default:
                        $response['result'] = "failed";
                        $response['code'] = 4;
                        $response['alert_message'] = "Check your information";
                        die(json_encode($response));exit;
                        break;
                    }
          
                    $message['id'] = $notification->notifications_id;
                    $message['title'] = $notification->title;
                    $message['notification_type_id'] = $notification->notification_type_id;
                    $message['status_id'] = $notification->status_id;
                    $message['date'] = $notification->date;
                    // $temp = array();
                    $temp['assistance'] = $body['assistance'];				
                    $temp['platform_categories'] = getPlatformCategories($body['platform_categories_id']);	
                    $message['body'] = $temp;
                    $message['user'] = getUser($notification->user_id);
                    array_push($notifications, $message);
                }

                $response['result'] = "success";
                $response['code'] = 1;
                $response['message'] = $notifications;

                die(json_encode($response));exit;

            } else {
                $response['result'] = "failed";
                $response['code'] = 4;
                $response['alert_message'] = "Not there notification.";

                die(json_encode($response));exit;
            }
        }
    }

    function selectAssistance($assistance_request_id) {
        global $conn;

        $sql_select_assistance = "SELECT * FROM assistance_request 
                                  WHERE assistance_request_id = :assistance_request_id";

        try{
            $stmt_assistance = $conn->prepare($sql_select_assistance);
            $stmt_assistance->bindparam(':assistance_request_id', $assistance_request_id, PDO::PARAM_STR);
            $stmt_assistance->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $assistance_info = $stmt_assistance->fetch();
        $assistance = array();
        $response = array();

        if($assistance_info && $stmt_assistance->rowCount() == 1){
            $platform['platform_categories_id'] = $assistance_info['platform_categories_id'];
            $platform['name_en'] = $assistance_info['name_en'];
            $platform['name_ar'] = $assistance_info['name_ar'];
            $platform['description_en'] = $assistance_info['description_en'];
            $platform['description_ar'] = $assistance_info['description_ar']; 
            $platform['acceptance_id'] = $assistance_info['acceptance_id'];           
            $platform['user_id'] = $assistance_info['user_id'];

            $assistance['assistance_request_id'] = $assistance_info['assistance_request_id'];
            $assistance['id_photo_url'] = $assistance_info['user_id_photo'];
            $assistance['platform'] = $platform;      
            
            $response['assistance'] = $assistance;
            $response['platform_categories_id'] = $assistance_info['platform_categories_id'];

            return $response;
        } else {    
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there assistance.";

            die(json_encode($response));exit;
        }
    }

    function getUser($user_id) {
        global $conn;    

        $sql_select_user = "SELECT * FROM  users WHERE user_id = :user_id";

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
            $message['id_photo'] = $user_info['id_photo'];

            return $message;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }

    
    function getPlatformCategories($platform_categories_id) {
        global $conn;    

        $sql_select_platform_categories = "SELECT * FROM  platform_categories WHERE id = :platform_categories_id";

        try {
            $stmt_platform_categories = $conn->prepare($sql_select_platform_categories);
            $stmt_platform_categories->bindParam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
            $stmt_platform_categories->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $platform_categories_info = $stmt_platform_categories->fetch();

        if($platform_categories_info && $stmt_platform_categories->rowCount() == 1){
            $message['id'] = $platform_categories_info['id'];
            $message['name_en'] = $platform_categories_info['name_en'];
            $message['name_ar'] = $platform_categories_info['name_ar'];
            $message['image'] = $platform_categories_info['image_path'];

            return $message;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }
?>