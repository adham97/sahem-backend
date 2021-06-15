<?php
    require_once 'config.php';

    function addDonationStore($donation_store) {
        global $conn;

        $sql_insert = "INSERT INTO donation_store (user_id, name_en, name_ar, description_en, description_ar, address_id, image, status) 
                       VALUE (:user_id, :name_en, :name_ar, :description_en, :description_ar, :address_id, :image, :status)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindparam(':user_id', $donation_store['user_id'], PDO::PARAM_STR);
            $stmt_insert->bindparam(':name_en', $donation_store['name_en'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':name_ar', $donation_store['name_ar'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':description_en', $donation_store['description_en'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':description_ar', $donation_store['description_ar'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':address_id', $donation_store['address_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':image', $donation_store['image'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':status', $donation_store['status'], PDO::PARAM_STR); 
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }

    function addCard($card) {
        global $conn;

        $user_id = $card['user_id'];
        $card_number = $card['card_number'];
        $expiry_date = $card['expiry_date'];
        $card_holder_name = $card['card_holder_name'];
        $cvv_code = $card['cvv_code'];
        $amount = $card['amount'];
        
        $sql_insert_card = "INSERT INTO cards (user_id, card_number, expiry_date, card_holder_name, cvv_code, amount) 
                            VALUE (:user_id, :card_number, :expiry_date, :card_holder_name, :cvv_code, :amount)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert_card);
            $stmt_insert->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert->bindparam(':card_number', $card_number, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':expiry_date', $expiry_date, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':card_holder_name', $card_holder_name, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':cvv_code', $cvv_code, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':amount', $amount, PDO::PARAM_STR);  
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }

    function addAddress($address) {
        global $conn;

        $user_id = $address['user_id'];
        $city  = $address['city'];
        $street = $address['street'];
        $latitude = $address['latitude'];
        $longitude = $address['longitude'];
        
        $sql_insert_address = "INSERT INTO addresses (user_id, city, street, latitude, longitude) 
                               VALUE (:user_id, :city, :street, :latitude, :longitude)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert_address);
            $stmt_insert->bindparam(':city', $city, PDO::PARAM_STR);
            $stmt_insert->bindparam(':street', $street, PDO::PARAM_STR); 
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
        return $conn->lastInsertId();
    }

    function addNotification($notification) {
        global $conn;

        $user_id = $notification['user_id'];
        $body_id = $notification['body_id'];
        $title  = $notification['title'];
        $notification_type_id = $notification['notification_type_id'];
        $status_id = $notification['status_id'];
        
        $sql_insert_notifications = "INSERT INTO notifications (user_id, body_id, title, notification_type_id, status_id) 
                                     VALUES (:user_id, :body_id, :title, :notification_type_id, :status_id)";

        try {
            $stmt_insert = $conn->prepare($sql_insert_notifications);
            $stmt_insert->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert->bindparam(':body_id', $body_id, PDO::PARAM_STR);
            $stmt_insert->bindparam(':title', $title, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':notification_type_id', $notification_type_id, PDO::PARAM_STR); 
            $stmt_insert->bindparam(':status_id', $status_id, PDO::PARAM_STR); 
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }   

    function updateNotification($notifications_id) {
        global $conn;
        
        $sql_update_notification = "UPDATE notifications SET status_id = 0 WHERE notifications_id = :notifications_id";

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
    }

    function getNotificationManager() {
        global $conn;
        
        $sql_select_number = "SELECT * FROM notifications WHERE notification_type_id = 1 ORDER BY notifications_id DESC";

        try {
            $stmt_notification = $conn->prepare($sql_select_number);
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
                $message['notifications_id'] = $notification->notifications_id ;
                $message['user'] = getUser($notification->user_id);
                $message['body'] = getAssistance($notification->body_id);
                $message['title'] = $notification->title;
                $message['notification_type_id'] = $notification->notification_type_id;
                $message['status_id'] = $notification->status_id;
                $message['date'] = $notification->date;

                array_push($notifications, $message);
            }

            return $notifications;
        } 
    }

    
    function countNotificationManager() {
        global $conn;
        
        $sql_select_number = "SELECT * FROM notifications WHERE status_id = 1 AND notification_type_id = 1";

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

        if($number_info && $stmt_number->rowCount() > 0){

            return count($number_info);
        }
    }

    
    function getNotificationUser($user_id) {
        global $conn;
        
        $sql_select_number = "SELECT * FROM notifications 
                              WHERE user_id = :user_id  
                              AND   notification_type_id = 2 
                              OR    user_id = :user_id  
                              AND   notification_type_id = 3
                              OR    user_id = :user_id  
                              AND   notification_type_id = 4
                              OR    user_id = :user_id  
                              AND   notification_type_id = 5
                              ORDER BY notifications_id DESC";

        try {
            $stmt_notification = $conn->prepare($sql_select_number);
            $stmt_notification->bindParam(':user_id', $user_id, PDO::PARAM_STR);
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
                $message['notifications_id'] = $notification->notifications_id ;
                $message['user'] = getUser($notification->user_id);
                if($notification->notification_type_id == '1' || $notification->notification_type_id == '2' || $notification->notification_type_id == '3')
                    $message['body'] = getAssistance($notification->body_id);                
                if($notification->notification_type_id == '4' || $notification->notification_type_id == '5') 
                    $message['body'] = getPlatform(getPayment($notification->body_id)['platform_id']);                
                $message['title'] = $notification->title;
                $message['notification_type_id'] = $notification->notification_type_id;
                $message['status_id'] = $notification->status_id;
                $message['date'] = $notification->date;

                array_push($notifications, $message);
            }
            return $notifications;
        } 
    }

    function countNotificationUser($user_id) {
        global $conn;
        
        $sql_select_number = "SELECT * FROM notifications 
                              WHERE status_id = 1 
                              AND user_id = :user_id 
                              AND notification_type_id != 1
                              AND notification_type_id != 6";

        try {
            $stmt_number = $conn->prepare($sql_select_number);
            $stmt_number->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_number->execute();

        } catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $number_info = $stmt_number->fetchAll(PDO::FETCH_OBJ);

        if($number_info && $stmt_number->rowCount() >= 1){

            return count($number_info);

        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there notification.";

            die(json_encode($response));exit;
        }
    }

    function countNotificationDriver() {
        global $conn;
        
        $sql_select_number = "SELECT * FROM notifications 
                              WHERE status_id = 1 
                              AND   notification_type_id == 6";

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

            return count($number_info);

        }
    }

    function getPlatformCategories($platform_categories_id) {
        global $conn;

        $sql_select = "SELECT * FROM platform_categories WHERE id = :platform_categories_id";
    
        try {
            $stmt_categories = $conn->prepare($sql_select);
            $stmt_categories->bindParam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
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

            return $message;
        }
    }

    function getPlatform($platform_id) {
        global $conn;

        $sql_select_platform = "SELECT * FROM platform WHERE id = :platform_id";
    
        try {
            $stmt_platform = $conn->prepare($sql_select_platform);
            $stmt_platform->bindParam(':platform_id', $platform_id, PDO::PARAM_STR);
            $stmt_platform->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }
    
        $platform_info = $stmt_platform->fetch();
        
        if($platform_info && $stmt_platform->rowCount() == 1) {
            $platform['id'] = $platform_info['platform_categories_id'];
            $platform['name_en'] = $platform_info['name_en'];
            $platform['name_ar'] = $platform_info['name_ar'];
            $platform['description_en'] = $platform_info['description_en'];
            $platform['description_ar'] = $platform_info['description_ar'];
            $platform['user_id'] = $platform_info['user_id'];
            $platform['date'] = $platform_info['date'];

            $message['platform'] = $platform;
            return $message;
        }
    }

    function getCards($user_id) {
        global $conn;

        $sql_select_cards = "SELECT * FROM cards WHERE user_id = :user_id";

        try {
            $stmt_cards = $conn->prepare($sql_select_cards);
            $stmt_cards->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_cards->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $cards_info = $stmt_cards->fetchAll(PDO::FETCH_OBJ);
        $cards = array();

        if($cards_info && $stmt_cards->rowCount() >= 1) {
            foreach($cards_info as $card) {
                $message['card_id'] = $card->card_id;
                $message['user_id'] = $card->user_id;
                $message['card_number'] = $card->card_number;
                $message['expiry_date'] = $card->expiry_date;
                $message['card_holder_name'] = $card->card_holder_name;
                $message['cvv_code'] = $card->cvv_code;
                $message['amount'] = $card->amount;

                array_push($cards, $message);
            }
            
            return $cards;
        
        } 
    }

    
    function getDonation($donation_store_id) {
        global $conn;

        $sql_select_donation = "SELECT * FROM donation_store WHERE donation_store_id = :donation_store_id";

        try {
            $stmt_donation = $conn->prepare($sql_select_donation);
            $stmt_donation->bindParam(':donation_store_id', $donation_store_id, PDO::PARAM_STR);
            $stmt_donation->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $donation_info = $stmt_donation->fetch();

        if($donation_info && $stmt_donation->rowCount() == 1) {
            $message['donation_store_id'] = $donation_info['donation_store_id'];
            $message['user_id'] = $donation_info['user_id'];
            $message['name_en'] = $donation_info['name_en'];
            $message['name_ar'] = $donation_info['name_ar'];
            $message['description_en'] = $donation_info['description_en'];            
            $message['description_ar'] = $donation_info['description_en'];
            $message['address_id'] = $donation_info['address_id'];
            $message['image'] = $donation_info['image'];
            $message['status'] = $donation_info['status'];
            
            return $message;
        } 
    }

    function getCard($card_id, $user_id) {
        global $conn;

        $sql_select_card = "SELECT * FROM cards WHERE card_id = :card_id AND user_id = :user_id";

        try {
            $stmt_card = $conn->prepare($sql_select_card);
            $stmt_card->bindParam(':card_id', $card_id, PDO::PARAM_STR);
            $stmt_card->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_card->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $card_info = $stmt_card->fetch();

        if($card_info && $stmt_card->rowCount() == 1) {
            $message['card_id'] = $card_info['card_id'];
            $message['user_id'] = $card_info['user_id'];
            $message['card_number'] = $card_info['card_number'];
            $message['expiry_date'] = $card_info['expiry_date'];
            $message['card_holder_name'] = $card_info['card_holder_name'];
            $message['cvv_code'] = $card_info['cvv_code'];
            $message['amount'] = $card_info['amount'];
            
            return json_encode($message);
        
        } 
    }

    function getAddress($address_id, $user_id) {
        global $conn;

        $sql_select_address = "SELECT * FROM addresses WHERE address_id = :address_id AND user_id = :user_id";

        try {
            $stmt_address = $conn->prepare($sql_select_address);
            $stmt_address->bindParam(':address_id', $address_id, PDO::PARAM_STR);
            $stmt_address->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_address->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }

        $address_info = $stmt_address->fetch();

        if($address_info && $stmt_address->rowCount() == 1) {
            $message['address_id'] = $address_info['address_id'];
            $message['user_id'] = $address_info['user_id'];
            $message['city'] = $address_info['city'];
            $message['street'] = $address_info['street'];
            $message['latitude'] = $address_info['latitude'];
            $message['longitude'] = $address_info['longitude'];
            
            return $message;
            
        } 
    }

    function getUser($user_id) {
        global $conn;

        $sql_select_user = "SELECT * FROM  users WHERE user_id = :user_id";

        try {
            $stmt_user = $conn->prepare($sql_select_user);
            $stmt_user->bindparam(':user_id', $user_id, PDO::PARAM_STR);
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
        } 
    }

    function getDriver() {
        global $conn;

        $sql_select_drivers = "SELECT user_id FROM  users WHERE user_role = 4";

        try {
            $stmt_drivers = $conn->prepare($sql_select_drivers);
            $stmt_drivers->execute(); 

        } catch (PDOException $ex) {  
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $drivers_info = $stmt_drivers->fetchAll(PDO::FETCH_OBJ);
        $drivers = array();

        if($drivers_info && $stmt_drivers->rowCount() >= 1){
            foreach($drivers_info as $driver) {
                array_push($drivers, $driver->user_id);
            }
            return $drivers;
        }
    }


    function addAssistance($assistance) {
        global $conn;

        $user_id = $assistance['user_id'];
        $user_id_photo = $assistance['user_id_pohto'];
        $platform_categories_id = $assistance['platform_categories_id'];
        $name_en = $assistance['name_en'];
        $name_ar = $assistance['name_ar'];
        $description_en = $assistance['description_en'];
        $description_ar = $assistance['description_ar'];
        $acceptance_id = $assistance['acceptance_id'];
        $card_id = $assistance['card_id'];
        $address_id = $assistance['address_id'];

        $sql_insert_assistance = "INSERT INTO assistance (user_id, user_id_photo, platform_categories_id, name_en, 
        name_ar, description_en, description_ar, acceptance_id, card_id, address_id) VALUES (:user_id, :user_id_photo, 
        :platform_categories_id, :name_en, :name_ar, :description_en, :description_ar, :acceptance_id, :card_id, :address_id)"; 
        
        try {
            $stmt_insert_assistance = $conn->prepare($sql_insert_assistance);
            $stmt_insert_assistance->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':user_id_photo', $user_id_photo, PDO::PARAM_STR);  
            $stmt_insert_assistance->bindparam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':name_en', $name_en, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':name_ar', $name_ar, PDO::PARAM_STR);  
            $stmt_insert_assistance->bindparam(':description_en', $description_en, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':description_ar', $description_ar, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':acceptance_id', $acceptance_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':card_id', $card_id, PDO::PARAM_STR);
            $stmt_insert_assistance->bindparam(':address_id', $address_id, PDO::PARAM_STR);
            $stmt_insert_assistance->execute();		
        
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }

    function addPlatform($platform) {
        global $conn;

        $platform_categories_id = $platform['platform_categories_id'];
        $user_id = $platform['user_id'];
        $name_en = $platform['name_en'];
        $name_ar = $platform['name_ar'];
        $description_en = $platform['description_en'];
        $description_ar = $platform['description_ar'];

        $sql_insert_platform = "INSERT INTO platform (platform_categories_id, user_id, name_en, name_ar, description_en, description_ar) 
                                VALUES (:platform_categories_id, :user_id, :name_en, :name_ar, :description_en, :description_ar)"; 
        
        try {
            $stmt_insert_platform = $conn->prepare($sql_insert_platform);
            $stmt_insert_platform->bindparam(':platform_categories_id', $platform_categories_id, PDO::PARAM_STR);
            $stmt_insert_platform->bindparam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_insert_platform->bindparam(':name_en', $name_en, PDO::PARAM_STR);
            $stmt_insert_platform->bindparam(':name_ar', $name_ar, PDO::PARAM_STR);  
            $stmt_insert_platform->bindparam(':description_en', $description_en, PDO::PARAM_STR);
            $stmt_insert_platform->bindparam(':description_ar', $description_ar, PDO::PARAM_STR);
            $stmt_insert_platform->execute();		
        
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }

    function getAssistance($assistance_id) {
        global $conn;

        $sql_select_assistance = "SELECT * FROM assistance WHERE assistance_id = :assistance_id";

        try{
            $stmt_assistance = $conn->prepare($sql_select_assistance);
            $stmt_assistance->bindparam(':assistance_id', $assistance_id, PDO::PARAM_STR);
            $stmt_assistance->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $assistance_info = $stmt_assistance->fetch();
        
        if($assistance_info && $stmt_assistance->rowCount() == 1){
            $assistance['assistance_id'] = $assistance_info['assistance_id'];
            $assistance['user_id'] = $assistance_info['user_id'];
            $assistance['user_id_photo'] = $assistance_info['user_id_photo'];
            $assistance['platform_categories_id'] = $assistance_info['platform_categories_id'];
            $assistance['name_en'] = $assistance_info['name_en'];
            $assistance['name_ar'] = $assistance_info['name_ar'];
            $assistance['description_en'] = $assistance_info['description_en'];
            $assistance['description_ar'] = $assistance_info['description_ar']; 
            $assistance['acceptance_id'] = $assistance_info['acceptance_id'];           
            $assistance['card_id'] = $assistance_info['card_id'];           
            $assistance['address_id'] = $assistance_info['address_id'];           

            $message['assistance'] = $assistance;
            return $message;
        }
    }

    function getAssistances() {
        global $conn;

        $sql_select_assistances = "SELECT * FROM assistance";

        try{
            $stmt_assistances = $conn->prepare($sql_select_assistances);
            $stmt_assistances->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $assistances_info = $stmt_assistances->fetchAll(PDO::FETCH_OBJ);
        $assistances = array();

        if($assistances_info && $stmt_assistances->rowCount() >= 1){
            foreach($assistances_info as $assistance) {
                $message['assistance_id'] = $assistance->assistance_id;
                $message['user_id'] = $assistance->user_id;
                $message['user_id_photo'] = $assistance->user_id_photo;
                $message['platform_categories_id'] = $assistance->platform_categories_id;
                $message['name_en'] = $assistance->name_en;
                $message['name_ar'] = $assistance->name_ar;
                $message['description_en'] = $assistance->description_en;
                $message['description_ar'] = $assistance->description_ar; 
                $message['acceptance_id'] = $assistance->acceptance_id;           
                $message['card_id'] = $assistance->card_id;           
                $message['address_id'] = $assistance->address_id;           
    
                array_push($assistances, $message);
            }
            return json_encode($assistances);
        }
    }

    function updateAssistances($assistance_id, $acceptance_id) {
        global $conn;

        $sql_update_assistance = "UPDATE assistance
                                  SET    acceptance_id = :acceptance_id 
                                  WHERE  assistance_id = :assistance_id"; 

        try {
            $stmt_update = $conn->prepare($sql_update_assistance);
            $stmt_update->bindparam(':assistance_id', $assistance_id, PDO::PARAM_STR);  
            $stmt_update->bindparam(':acceptance_id', $acceptance_id, PDO::PARAM_STR);
            $stmt_update->execute();		
        
        }  catch (PDOException $ex) {	
        
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;

            die(json_encode($response));exit;
        }
    }
   
    function addCashPayment($payment) {
        global $conn;

        $sql_insert_payment = "INSERT INTO payments (price, card_id, user_id, payment_method_id, platform_id) 
                               VALUES (:price, :card_id, :user_id, :payment_method_id, :platform_id)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert_payment);
            $stmt_insert->bindparam(':price', $payment['price'], PDO::PARAM_STR);
            $stmt_insert->bindparam(':card_id', $payment['card_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':user_id', $payment['user_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':payment_method_id', $payment['payment_method_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':platform_id', $payment['platform_id'], PDO::PARAM_STR); 
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

        $response['result'] = "failed";
        $response['code'] = 3;
        $response['alert_message'] = "Try Agin, error DB: ".$ex;

        die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }
    
    function addMaterialPayment($payment) {
        global $conn;
        
        $sql_insert_payment = "INSERT INTO payments (description, address_id, user_id, payment_method_id, platform_id) 
                               VALUES (:description, :address_id, :user_id, :payment_method_id, :platform_id)"; 

        try {
            $stmt_insert = $conn->prepare($sql_insert_payment);
            $stmt_insert->bindparam(':description', $payment['description'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':address_id', $payment['address_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':user_id', $payment['user_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':payment_method_id', $payment['payment_method_id'], PDO::PARAM_STR); 
            $stmt_insert->bindparam(':platform_id', $payment['platform_id'], PDO::PARAM_STR); 
            $stmt_insert->execute();		

        }  catch (PDOException $ex) {	

        $response['result'] = "failed";
        $response['code'] = 3;
        $response['alert_message'] = "Try Agin, error DB: ".$ex;

        die(json_encode($response));exit;
        }
        return $conn->lastInsertId();
    }

    function getPayment($payment_id) {
        global $conn;
        
        $sql_select_payment = "SELECT * FROM payments WHERE id = :payment_id"; 

        try {
            $stmt_select = $conn->prepare($sql_select_payment);
            $stmt_select->bindparam(':payment_id', $payment_id, PDO::PARAM_STR); 
            $stmt_select->execute();		

        }  catch (PDOException $ex) {	

        $response['result'] = "failed";
        $response['code'] = 3;
        $response['alert_message'] = "Try Agin, error DB: ".$ex;

        die(json_encode($response));exit;
        }
        
        $payment_info = $stmt_select->fetch();

        if($payment_info && $stmt_select->rowCount() == 1){
            $message['user_id'] = $payment_info['user_id'];
            $message['platform_id'] = $payment_info['platform_id'];
            $message['description'] = $payment_info['description'];
            $message['address_id'] = $payment_info['address_id'];

            return $message;
        }
    }
?>