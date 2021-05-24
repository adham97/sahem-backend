<?php
    if(isset($_POST['user_id']) && isset($_POST['token_id']) && isset($_POST['id_photo']) && isset($_POST['name'])){
        require_once 'config.php';

        $user_id = check_security($_POST['user_id'], 'string');
        $id_photo = check_security($_POST['id_photo'], 'string');
        $name = check_security($_POST['name'], 'string');

        if(!empty($user_id) && !empty($id_photo) && !empty($name)){
            require_once 'auth_login.php';
            $user = is_login();

            $realImage = base64_decode($id_photo);
            $image_path = 'images/user_id_photo/'.$name;
            file_put_contents($image_path, $realImage);
            
            $sql_update = "UPDATE users 
                           SET    id_photo = :id_photo
                           WHERE  user_id = :user_id"; 

            try {
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindparam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt_update->bindparam(':id_photo', $image_path, PDO::PARAM_STR);  
                $stmt_update->execute();		
					
            }  catch (PDOException $ex) {	
					
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "Try Agin, error DB: ".$ex;
            
                die(json_encode($response));exit;
            }
            
            require_once 'user_info.php';
       
        } else {

            $response['result'] = "failed";
            $response['code'] = 0;
            $response['alert_message'] = "Fields Require";

            die(json_encode($response));exit;
        }

    } else {

		$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
	}
?>