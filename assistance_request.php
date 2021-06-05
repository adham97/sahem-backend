<?php

	// $_POST['platform_categories_id'] = "2";
    // $_POST['user_id_photo'] = "hi";
    // $_POST['photo_name'] = 'adham';
	// $_POST['user_id'] = "2";
	// $_POST['option'] = "en";
	// $_POST['name'] = "Two blood units";
	// $_POST['description'] = "Blood Type A + at Rafidia Hospital";

	if(isset($_POST['platform_categories_id']) && isset($_POST['user_id_photo']) && isset($_POST['photo_name']) 
    && isset($_POST['user_id']) && isset($_POST['option'])) {

		require_once 'config.php';
		$platform_categories_id = check_security($_POST['platform_categories_id'], 'string');
        $user_id = check_security($_POST['user_id'], 'string');
        $user_id_photo = check_security($_POST['user_id_photo'], 'string');
        $photo_name = check_security($_POST['photo_name'], 'string');
        $realImage = base64_decode($user_id_photo);
        $image_path = 'images/user_id_photo/'.$photo_name;
        file_put_contents($image_path, $realImage);
        $option = check_security($_POST['option'], 'string');

        switch ($option) {
            case 'en':
                if(!empty(isset($_POST['name'])) && !empty(isset($_POST['description']))) {
                    require_once 'translation.php';
                    $name_en = check_security($_POST['name'], 'string');
                    $name_ar = Translation('en', 'ar', $name_en);
                    $description_en = check_security($_POST['description'], 'string'); 
                    $description_ar = Translation('en', 'ar', $description_en);  
                    // echo  assistance($platform_categories_id, $user_id, $name_en, $name_ar, $description_en, $description_ar);
                }
                break;
            case 'ar':
                if(!empty(isset($_POST['name'])) && !empty(isset($_POST['description']))) {
                    require_once 'translation.php';
                    $name_ar = check_security($_POST['name'], 'string');
                    $name_en = Translation('ar', 'en', $name_ar);
                    $description_ar = check_security($_POST['description'], 'string'); 
                    $description_en = Translation('ar', 'en', $description_ar);  
                    // echo assistance($platform_categories_id, $user_id, $name_en, $name_ar, $description_en, $description_ar);
                }
                break;
            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }

        $sql_insert_assistance = "INSERT INTO assistance_request (user_id, user_id_photo, platform_categories_id, name_en, 
        name_ar, description_en, description_ar, acceptance_id) VALUES (:user_id, :user_id_photo, :platform_categories_id, :name_en, 
        :name_ar, :description_en, :description_ar, :acceptance_id)"; 

        $acceptance_id = '1';
        
        try {
            $stmt_insert_assistance = $conn->prepare($sql_insert_assistance);
            $stmt_insert_assistance->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':user_id_photo', $image_path, PDO::PARAM_STR);  
            $stmt_insert_assistance->bindparam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':name_en', $name_en, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':name_ar', $name_ar, PDO::PARAM_STR);  
            $stmt_insert_assistance->bindparam(':description_en', $description_en, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':description_ar', $description_ar, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':acceptance_id', $acceptance_id, PDO::PARAM_STR);
            $stmt_insert_assistance->execute();		
        
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $sql_insert_notifications = "INSERT INTO notifications (body, user_id, title, notification_type_id, status_id) 
        VALUES (:body, :user_id, :title, :notification_type_id, :status_id)"; 
        $body = $conn->lastInsertId();
        $status_id = 1;
        $title = 'send assistance request';
        $notification_type_id = 1;
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
        $response['message'] = "Insert Assistance request Done !";
        
        die(json_encode($response));exit;
       
	} else {
		$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
	}
?>	