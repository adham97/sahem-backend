<?php
    if ( !empty(isset($_POST['token_id'])) ) {		
        require_once 'auth_login.php';
        $user = is_login();

        if( !empty(isset($_POST['user_id'])) ) {
            $user_id = check_security($_POST['user_id'], 'string');
            
            require_once 'helper.php';
            $cards = getCards($user_id);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $cards;

            die(json_encode($response));exit;
        }

        if( !empty(isset($_POST['card'])) ) {
            $card_obj = json_decode($_POST['card']);

            foreach ($card_obj as $element) 
                $card_array[] = $element;
    
            require_once 'config.php';
            $card['user_id'] = check_security($card_array[0], 'string');
            $card['card_number'] = check_security($card_array[1], 'string');
            $card['expiry_date'] = check_security($card_array[2], 'string');
            $card['card_holder_name'] = check_security($card_array[3], 'string');
            $card['cvv_code'] = check_security($card_array[4], 'string');
            $card['amount'] = check_security($card_array[5], 'string');

            require_once 'helper.php';
            $card_id = addCard($card);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $card_id;

            die(json_encode($response));exit;
        }
    }
?>