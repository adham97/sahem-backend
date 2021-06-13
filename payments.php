<?php
    if( !empty(isset($_POST['token_id'])) ) {
        require_once 'auth_login.php';
        $user = is_login();

        if( !empty(isset($_POST['payment'])) ) {

            $payment_obj = json_decode($_POST['payment']);

            foreach ($payment_obj as $pay) 
                $payment_array[] = $pay;
      
            $payment['price'] = check_security($payment_array[0], 'string');
            $payment['card_id'] = check_security($payment_array[1], 'string');
            $payment['description'] = check_security($payment_array[2], 'string');
            $payment['address_id'] = check_security($payment_array[3], 'string');
            $payment['user_id'] = check_security($payment_array[4], 'string');
            $payment['status'] = check_security($payment_array[5], 'string');
            $payment['payment_method_id'] = check_security($payment_array[6], 'string');
            $payment['platform_id'] = check_security($payment_array[7], 'string');

            require_once 'helper.php';

            switch ($payment['payment_method_id']) {
                case '1':
                    $cash_payment_id = addCashPayment($payment);
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $cash_payment_id;

                    $platform = getPlatform($payment['platform_id']);

                    $notification['user_id'] = $payment['user_id'];
                    $notification['body_id'] = $cash_payment_id;
                    $notification['title'] = "A donation of " . $payment['price'] . "$ has been successfully delivered to " . $platform['platform']['name_en'];
                    $notification['notification_type_id'] = "4";
                    $notification['status_id'] = "1";
            
                    addNotification($notification);

                    $notification['user_id'] = $platform['platform']['user_id'];
                    $notification['body_id'] = $cash_payment_id;
                    $notification['title'] = "A sum of " . $payment['price'] . "$ was donated at your request to the " . $platform['platform']['name_en'];
                    $notification['notification_type_id'] = "4";
                    $notification['status_id'] = "1";
            
                    addNotification($notification);

                    die(json_encode($response));exit;
                    break;
                
                case '2':
                    $material_payment_id = addMaterialPayment($payment);
     
                    $platform = getPlatform($payment['platform_id']);
                    
                    $notification['user_id'] = $payment['user_id'];
                    $notification['body_id'] = $material_payment_id;
                    $notification['title'] = "Our driver will come to you as soon as possible to collect the package";
                    $notification['notification_type_id'] = "5";
                    $notification['status_id'] = "1";
            
                    addNotification($notification);

                    $user = getUser($payment['user_id']);
                    $drivers = getDriver();
                    
                    for($i = 0; $i < count($drivers); $i++) {

                        $notification['user_id'] = $drivers[$i];
                        $notification['body_id'] = $material_payment_id;
                        $notification['title'] = "Receiving a parcel from " . $user['first_name'] . " " . $user['last_name'];
                        $notification['notification_type_id'] = "6";
                        $notification['status_id'] = "1";
                
                        addNotification($notification);
                    }

                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $material_payment_id;

                    die(json_encode($response));exit;
                    break;
        
                default:
                    # code...
                    break;
            }
        }
    }
?>