<?php
    if( !empty(isset($_POST['token_id'])) ) {
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $sql_select = "SELECT * FROM platform_categories";

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