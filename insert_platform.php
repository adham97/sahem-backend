<?php
	if(!empty(isset($_POST['token_id'])) && !empty(isset($_POST['platform']))) {
		require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $platform = json_decode($_POST['platform']);

        foreach ($platform as $value) 
            $array[] = $value;

		$platform_categories_id  = check_security($array[1], 'string');
        $name_en = check_security($array[2], 'string');
		$name_ar = check_security($array[3], 'string');
		$description_en = check_security($array[4], 'string');
		$description_ar = check_security($array[5], 'string');
		$user_id = check_security($array[7], 'string');
        
        $sql_insert_platform = "INSERT INTO platform (name_en, name_ar, description_en, description_ar, 
        platform_categories_id, user_id) VALUE (:name_en, :name_ar, :description_en, :description_ar, 
        :platform_categories_id, :user_id)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert_platform);
            $stmt_insert->bindparam(':name_en', $name_en, PDO::PARAM_STR);
            $stmt_insert->bindparam(':name_ar', $name_ar, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':description_en', $description_en, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':description_ar', $description_ar, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':user_id', $user_id, PDO::PARAM_STR);  
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        
        $response['result'] = "success";
		$response['code'] = 1;
		$response['message'] = "Insert Assistance Done";

        die(json_encode($response));exit;

	}
?>	