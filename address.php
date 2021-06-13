<?php
    if ( !empty(isset($_POST['token_id'])) && !empty(isset($_POST['address'])) ) {		
        require_once 'auth_login.php';
        $user = is_login();

        $address_obj = json_decode($_POST['address']);

        foreach ($address_obj as $element) 
            $address_array[] = $element;
  
        require_once 'config.php';
        $address['user_id'] = check_security($address_array[0], 'string');
        $address['city'] = check_security($address_array[1], 'string');
        $address['street'] = check_security($address_array[2], 'string');
        $address['latitude'] = check_security($address_array[3], 'string');
        $address['longitude'] = check_security($address_array[4], 'string');
            
        require_once 'helper.php';
        $address_id = addAddress($address);

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = $address_id;
        
        die(json_encode($response));exit;
    }
?>