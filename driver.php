<?php
    if( !empty(isset($_POST['token_id'])) &&  ) {
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        if( !empty(isset($_POST['role'])) && !empty(isset($_POST['role'])) == '4' ) {
            $role = check_security($_POST['role'], 'string');

            $sql_select_order = "SELECT * FROM notifications WHERE notification_type_id = 6 OR notification_type_id = 8 ORDER BY notifications_id DESC";
    
            try {
                $stmt_order = $conn->prepare($sql_select_order);
                $stmt_order->execute();
    
            } catch (PDOException $ex) {	
    
                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "error DB: ".$ex;
    
                die(json_encode($response));exit;
            }
    
            $orders_info = $stmt_order->fetchAll(PDO::FETCH_OBJ);
            $orders = array();
    
            require_once 'helper.php';
            if($orders_info && $stmt_order->rowCount() >= 1){
                foreach($orders_info as $order) {
                    $message['id'] = $order->notifications_id;
                    $message['user'] = getUser($order->user_id);
                    $message['title'] = $order->title;
                    $message['type_id'] = $order->notification_type_id;
                    $message['status_id'] = $order->status_id;
                    $message['date'] = $order->date;

                    if($order->notification_type_id == '6') {
                        $payment = getPayment($order->body_id);
                        $platform = getPlatform(getPayment($order->body_id)['platform_id'])['platform'];  

                        $message['name'] = $platform['name_en'];
                        $message['description'] = $payment['description'];
                        $message['address'] = getAddress($payment['address_id'], $order->user_id);
                        $message['image'] = getPlatformCategories($platform['id'])['image'];

                    } else if($order->notification_type_id == '8') {
                        $donation = getDonation($order->body_id);

                        $message['name'] = $donation['name_en'];
                        $message['description'] = $donation['description_en'];
                        $message['address'] = getAddress($donation['address_id'], $donation['user_id']);
                        $message['image'] = $donation['image'];
                    }

                   
    
                    array_push($orders, $message);
                }
                $response['result'] = "success";
                $response['code'] = 1;
                $response['message'] = $orders;

                die(json_encode($response));exit;
            } 
        }
    }
?>