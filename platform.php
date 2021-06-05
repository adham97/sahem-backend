<?php
    if(!empty(isset($_POST['platform_id']))) {
        require_once 'config.php';
        $platform_id = check_security($_POST['platform_id'], "string");
        
        $sql_select = "SELECT * FROM platform WHERE id = :platform_id";

        try {
            $stmt_platform = $conn->prepare($sql_select);
            $stmt_platform->bindParam(':platform_id', $platform_id, PDO::PARAM_STR);
            $stmt_platform->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $platforms_info = $stmt_platform->fetch();
        
        if($platforms_info && $stmt_platform->rowCount() >= 1) {
        
            $message['id'] = $platforms_info['id'];
            $message['name_en'] = $platforms_info['name_en'];
            $message['name_ar'] = $platforms_info['name_ar'];
            $message['description_en'] = $platforms_info['description_en'];
            $message['description_ar'] = $platforms_info['description_ar'];
            $message['user_id'] = $platforms_info['user_id'];
            $message['created_at'] = $platforms_info['created_at'];

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $message;

            die(json_encode($response));exit;
        
        } else {
            $response['result'] = "failed";
            $response['code'] = 0;
            $response['alert_message'] = "Try agin, error DB Paltform Gategories";

            die(json_encode($response));exit;
        }
    } else if(!empty(isset($_POST['platform_categories_id']))){
        require_once 'config.php';
        $platform_categories_id = check_security($_POST['platform_categories_id'], "string");
        
        $sql_select = "SELECT * FROM platform WHERE platform_categories_id = :platform_categories_id";

        try {
            $stmt_platform = $conn->prepare($sql_select);
            $stmt_platform->bindParam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
            $stmt_platform->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $platforms_info = $stmt_platform->fetchAll(PDO::FETCH_OBJ);
        $platforms = array();

        if($platforms_info && $stmt_platform->rowCount() >= 1) {
            foreach($platforms_info as $platform) {

                $message['id'] = $platform->id;
                $message['name_en'] = $platform->name_en;
                $message['name_ar'] = $platform->name_ar;
                $message['description_en'] = $platform->description_en;
                $message['description_ar'] = $platform->description_ar;
                // $message['acceptance_id'] = $platform->acceptance_id;
                $message['user_id'] = $platform->user_id;
                $message['created_at'] = $platform->created_at;
  
                array_push($platforms, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $platforms;

            die(json_encode($response));exit;
        
        } else {
            $response['result'] = "failed";
            $response['code'] = 0;
            $response['alert_message'] = "Try agin, error DB Paltform Gategories";

            die(json_encode($response));exit;
        }
    }
    
?>