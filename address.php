<?php
    if (empty(isset($_POST['token_id'])) && empty(isset($_POST['user_id'])) && empty(isset($_POST['option']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $user_id = check_security($_POST['user_id'], 'string');
        $option = check_security($_POST['option'], 'string');

        switch ($option) {
            case 'select':
                selectAddress($user_id);
                break;
            
            case 'insert':
                insertAddress();
                break;

            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }       
    }

    function selectAddress($user_id) { 
        global $conn;

        $sql_select_address = "SELECT * FROM delivery_addresses WHERE user_id = :user_id";

        try {
            $stmt_address = $conn->prepare($sql_select_address);
            $stmt_address->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_address->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $address_info = $stmt_address->fetchAll(PDO::FETCH_OBJ);
        $addresses = array();

        if($address_info && $stmt_address->rowCount() >= 1){
            foreach($address_info as $address){
                $message['address_id'] = $address->address_id;				
                $message['locality'] = $address->locality;
                $message['postal_code'] = $address->postal_code;
                $message['country'] = $address->country;
                $message['latitude'] = $address->latitude;
                $message['longitude'] = $address->longitude;
                $message['user_id'] = $address->user_id;
                
                array_push($addresses, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $addresses;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there card";

            die(json_encode($response));exit;
        }
    }

    function insertAddress() {
        global $conn;

        if(!empty(isset($_POST['address']))) {

            $addresses = json_decode($_POST['address']);

            foreach ($addresses as $address) 
                $array[] = $address;
      
            $locality = check_security($array[0], 'string');
            $postal_code = check_security($array[1], 'string');
            $country = check_security($array[2], 'string');
            $latitude = check_security($array[3], 'string');
            $longitude  = check_security($array[4], 'string');
            $user_id = check_security($array[5], 'string');
            
            $sql_insert_address = "INSERT INTO delivery_addresses (locality, postal_code, country, latitude, longitude, user_id) 
            VALUE (:locality, :postal_code, :country, :latitude, :longitude, :user_id)"; 
    
            try {
                $stmt_insert = $conn->prepare($sql_insert_address);
                $stmt_insert->bindparam(':locality', $locality, PDO::PARAM_STR);
                $stmt_insert->bindparam(':postal_code', $postal_code, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':country', $country, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':latitude', $latitude, PDO::PARAM_STR); 
                $stmt_insert->bindparam(':longitude', $longitude, PDO::PARAM_STR); 
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
            $response['message'] = $conn->lastInsertId();
    
            die(json_encode($response));exit;
        }
   }
?>