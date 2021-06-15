<?php
    // $_POST['token_id'] = 'aEpUazdjOHNIOCEkMDgvMDYvMjEgMDQ6MTM6MjAhJGRpbWFAZ21haWwuY29tISQxOTIuMTY4LjEuMTUz';
    // $_POST['i_need_it'] = 'i_need_it';
    // $_POST['donation_store_id'] = '2';
    // $_POST['user_id'] = '8';
    
    if( !empty(isset($_POST['token_id'])) ) {
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();
   
        if( !empty(isset($_POST['select_donation_store'])) ) {
            require_once 'helper.php';

            $sql_donation_store = "SELECT * FROM donation_store WHERE status = 1";

            try {
                $stmt_donation_store = $conn->prepare($sql_donation_store);
                $stmt_donation_store->execute();		
                
            }  catch (PDOException $ex) {	
                
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "Try Agin, error DB: ".$ex;
            
                die(json_encode($response));exit;
            }
    
            $donation_store_info = $stmt_donation_store->fetchAll(PDO::FETCH_OBJ);
            $donations = array();
    
            if($donation_store_info && $stmt_donation_store->rowCount() >= 1) {
                foreach($donation_store_info as $donation) {
    
                    $message['donation_store_id'] = $donation->donation_store_id;
                    $message['user_id'] = $donation->user_id;
                    $message['name_en'] = $donation->name_en;
                    $message['name_ar'] = $donation->name_ar;
                    $message['description_en'] = $donation->description_en;
                    $message['description_ar'] = $donation->description_ar;
                    $message['address_id'] = $donation->address_id;
                    $message['image'] = $donation->image;
      
                    array_push($donations, $message);
                }
    
                $response['result'] = "success";
                $response['code'] = 1;
                $response['message'] = $donations;
    
                die(json_encode($response));exit;
            
            } else {
                $response['result'] = "failed";
                $response['code'] = 0;
                $response['alert_message'] = "Try agin, error DB Paltform Gategories";
    
                die(json_encode($response));exit;
            }
        }

        if( !empty(isset($_POST['option'])) && !empty(isset($_POST['donation_store'])) && !empty(isset($_POST['address'])) ) {

             // add address
            $address_obj = json_decode($_POST['address']);

            foreach ($address_obj as $element) 
                $address_array[] = $element;
    
            $address['user_id'] = check_security($address_array[0], 'string');
            $address['city'] = check_security($address_array[1], 'string');
            $address['street'] = check_security($address_array[2], 'string');
            $address['latitude'] = check_security($address_array[3], 'string');
            $address['longitude'] = check_security($address_array[4], 'string');

            require_once 'helper.php';
                
            $address_id = addAddress($address);

            $option = check_security($_POST['option'], 'string');

            $donation_store_obj = json_decode($_POST['donation_store']);
    
            foreach ($donation_store_obj as $element) 
                $donation_store_array[] = $element;
    
            $donation_store['user_id'] = check_security($donation_store_array[0], 'string');
            $donation_store['name_en'] = check_security($donation_store_array[1], 'string');
            $donation_store['name_ar'] = check_security($donation_store_array[2], 'string');
            $donation_store['description_en'] = check_security($donation_store_array[3], 'string');
            $donation_store['description_ar'] = check_security($donation_store_array[4], 'string');
            $donation_store['address_id'] = $address_id;
            $donation_store['status'] = '1';
            
            require_once 'translation.php';

            switch ($option) {
                case 'en':
                    $donation_store['name_ar'] = Translation('en', 'ar', $donation_store['name_en']);
                    $donation_store['description_ar'] = Translation('en', 'ar', $donation_store['description_en']);  
                    break;
                case 'ar':
                    $donation_store['name_en'] = Translation('ar', 'en', $donation_store['name_ar']);
                    $donation_store['description_en'] = Translation('ar', 'en', $donation_store['description_ar']);  
                    break;
                default:
                    $response['result'] = "failed";
                    $response['code'] = 6;
                    $response['alert_message'] = "These privacy shortcuts are confidential";

                    die(json_encode($response));exit;
                    break;
            }

            $image_path = 'images/donation_store/'. $donation_store['name_en'] . '.png';
            $real_image = base64_decode(check_security($donation_store_array[6], 'string'));
            file_put_contents($image_path, $real_image);

            $donation_store['image'] = $image_path;

            $donation_store_id = addDonationStore($donation_store);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $donation_store_id;

            die(json_encode($response));exit;
        }

        if( !empty(isset($_POST['i_need_it'])) && !empty(isset($_POST['donation_store_id'])) && !empty(isset($_POST['user_id'])) ) {

            $donation_store_id = check_security($_POST['donation_store_id'], "string");
            $user_id = check_security($_POST['user_id'], "string");

            $sql_update = "UPDATE donation_store SET status = 0 WHERE donation_store_id = :donation_store_id";

            try {
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindParam(':donation_store_id', $donation_store_id, PDO::PARAM_STR);
                $stmt_update->execute();		
                
            }  catch (PDOException $ex) {	
                
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "Try Agin, error DB: ".$ex;
            
                die(json_encode($response));exit;
            }

            require_once 'helper.php';
            $donation = getDonation($donation_store_id);

            $notification['user_id'] = $donation['user_id'];
            $notification['body_id'] = $donation['donation_store_id'];
            $notification['title'] = "Our driver will come to you as soon as possible to collect the ". $donation['name_en'];
            $notification['notification_type_id'] = "7";
            $notification['status_id'] = "1";
    
            addNotification($notification);
            
            $user = getUser($donation['user_id']);
            
            $notification['user_id'] = $user_id;
            $notification['body_id'] = $donation['donation_store_id'];
            $notification['title'] = "Receiving a parcel from " . $user['first_name'] . " " . $user['last_name'];
            $notification['notification_type_id'] = "8";
            $notification['status_id'] = "1";
    
            addNotification($notification);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $donation_store_id;

            die(json_encode($response));exit;
    
        }
    }
?>