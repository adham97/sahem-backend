<?php
	if( !empty(isset($_POST['token_id'])) && !empty(isset($_POST['assistance'])) && !empty(isset($_POST['option'])) 
        && !empty(isset($_POST['name'])) && !empty(isset($_POST['card'])) && !empty(isset($_POST['address'])) ) {
        require_once 'auth_login.php';
        $user = is_login();

        // add card
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

        // add address
        $address_obj = json_decode($_POST['address']);

        foreach ($address_obj as $element) 
            $address_array[] = $element;
  
        $address['user_id'] = check_security($address_array[0], 'string');
        $address['city'] = check_security($address_array[1], 'string');
        $address['street'] = check_security($address_array[2], 'string');
        $address['latitude'] = check_security($address_array[3], 'string');
        $address['longitude'] = check_security($address_array[4], 'string');
            
        $address_id = addAddress($address);

        // add assistance
        $assistance_obj = json_decode($_POST['assistance']);
        $option = check_security($_POST['option'], 'string');
        $image_path = 'images/user_id_photo/'. check_security($_POST['name'], 'string');

        foreach ($assistance_obj as $element) 
            $assistance_array[] = $element;

        $assistance['user_id'] = check_security($assistance_array[1], 'string');
        $assistance['user_id_pohto'] = $image_path;
        $assistance['platform_categories_id'] = check_security($assistance_array[3], 'string');
        $assistance['name_en'] = check_security($assistance_array[4], 'string');
        $assistance['name_ar'] = check_security($assistance_array[5], 'string');
        $assistance['description_en'] = check_security($assistance_array[6], 'string');
        $assistance['description_ar'] = check_security($assistance_array[7], 'string');
        $assistance['acceptance_id'] = check_security($assistance_array[8], 'string');
        $assistance['card_id'] = $card_id;
        $assistance['address_id'] = $address_id;

        $real_image = base64_decode(check_security($assistance_array[2], 'string'));
        file_put_contents($image_path, $real_image);

        require_once 'translation.php';

        switch ($option) {
            case 'en':
                $assistance['name_ar'] = Translation('en', 'ar', $assistance['name_en']);
                $assistance['description_ar'] = Translation('en', 'ar', $assistance['description_en']);  
                break;
            case 'ar':
                $assistance['name_en'] = Translation('ar', 'en', $assistance['name_ar']);
                $assistance['description_en'] = Translation('ar', 'en', $assistance['description_ar']);  
                break;
            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }

        $assistance_id = addAssistance($assistance);

        $platform_categories = getPlatformCategories($assistance['platform_categories_id']);
        
        $notification['user_id'] = $assistance['user_id'];
        $notification['body_id'] = $assistance_id;
        $notification['title'] = "send a request for ". $platform_categories['name_en'] ." assistance";
        $notification['notification_type_id'] = "1";
        $notification['status_id'] = "1";

        addNotification($notification);

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = $assistance_id;

        die(json_encode($response));exit;

	} else {
		$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
	}
?>	