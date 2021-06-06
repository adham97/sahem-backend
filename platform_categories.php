<?php
    // $_POST['platform_categories_id'] = '2';
    if(!empty(isset($_POST['platform_categories_id']))) {

        require_once 'config.php';
        $sql_select = "SELECT * FROM `platform_categories` WHERE id = :id";
    
        try {
            $stmt_categories = $conn->prepare($sql_select);
            $stmt_categories->bindParam(':id', $_POST['platform_categories_id'], PDO::PARAM_STR);
            $stmt_categories->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }
    
        $categories = $stmt_categories->fetch();
        
        if($categories && $stmt_categories->rowCount() == 1) {
            $message['id'] = $categories['id'];
            $message['name_en'] = $categories['name_en'];
            $message['name_ar'] = $categories['name_ar'];
            $message['image'] = $categories['image_path'];

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
    } else {
        require_once 'config.php';
        $sql_select = "SELECT * FROM `platform_categories`";

        try {
            $stmt_categories = $conn->prepare($sql_select);
            $stmt_categories->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $categories = $stmt_categories->fetchAll(PDO::FETCH_OBJ);
        $platform_categories = array();
        
        if($categories && $stmt_categories->rowCount() >= 1) {
            foreach($categories as $category) {
                $message['id'] = $category->id;
                $message['name_en'] = $category->name_en;
                $message['name_ar'] = $category->name_ar;
                $message['image'] = $category->image_path;

                array_push($platform_categories, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $platform_categories;

            die(json_encode($response));exit;
        
        } else {
            $response['result'] = "failed";
            $response['code'] = 0;
            $response['alert_message'] = "Try agin, error DB Paltform Gategories";

            die(json_encode($response));exit;
        }
    }
?>