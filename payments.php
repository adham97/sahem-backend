<?php
    if (empty(isset($_POST['token_id'])) && empty(isset($_POST['payment']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $payments = json_decode($_POST['payment']);

        foreach ($payments as $payment) 
            $array[] = $payment;
  
        $price = check_security($array[0], 'string');
        $card_id = check_security($array[1], 'string');
        $description = check_security($array[2], 'string');
        $address_id = check_security($array[3], 'string');
        $user_id = check_security($array[4], 'string');
        $status  = check_security($array[5], 'string');
        $payment_method_id = check_security($array[6], 'string');
        $platform_id = check_security($array[7], 'string');
  
        if($payment_method_id == '1') {
            $sql_insert_payment = "INSERT INTO payments (price, card_id, user_id, payment_method_id, platform_id) 
                                   VALUES (:price, :card_id, :user_id, :payment_method_id, :platform_id)"; 
    
            $status = '1';
         
            try {
                $stmt_insert = $conn->prepare($sql_insert_payment);
                $stmt_insert->bindparam(':price', $price, PDO::PARAM_STR);
                $stmt_insert->bindparam(':card_id', $card_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':user_id', $user_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':payment_method_id', $payment_method_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':platform_id', $platform_id, PDO::PARAM_STR); 
                $stmt_insert->execute();		
    
            }  catch (PDOException $ex) {	
    
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "Try Agin, error DB: ".$ex;
    
                die(json_encode($response));exit;
            }
            
            $sql_insert_notifications = "INSERT INTO notifications (body, user_id, title, notification_type_id, status_id) 
            VALUES (:body, :user_id, :title, :notification_type_id, :status_id)"; 
            $body = $conn->lastInsertId();
            $status_id = '1';
            $title = 'donate a money to the';
            $notification_type_id = '4';
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
            $response['message'] = "Insert Payment Done";
    
            die(json_encode($response));exit;

        } else if($payment_method_id == '2') { 
            $sql_insert_payment = "INSERT INTO payments (description, address_id, user_id, status, payment_method_id, platform_id) 
                                   VALUES (:description, :address_id, :user_id, :status, :payment_method_id, :platform_id)"; 
    
            $status = '1';
         
            try {
                $stmt_insert = $conn->prepare($sql_insert_payment);
                $stmt_insert->bindparam(':description', $description, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':address_id', $address_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':user_id', $user_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':status', $status, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':payment_method_id', $payment_method_id, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':platform_id', $platform_id, PDO::PARAM_STR); 
                $stmt_insert->execute();		
    
            }  catch (PDOException $ex) {	
    
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "Try Agin, error DB: ".$ex;
    
                die(json_encode($response));exit;
            }
            
            $sql_insert_notifications = "INSERT INTO notifications (body, user_id, title, notification_type_id, status_id) 
            VALUES (:body, :user_id, :title, :notification_type_id, :status_id)"; 
            $body = $conn->lastInsertId();
            $status_id = '1';
            $title = 'donate a parcel to the';
            $notification_type_id = '4';
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
            $response['message'] = "Insert Payment Done";
    
            die(json_encode($response));exit;
        }       
    }
?>