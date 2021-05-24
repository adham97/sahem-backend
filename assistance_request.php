<?php

	// $_POST['platform_categories_id'] = "2";
	// $_POST['user_id'] = "2";
	// $_POST['option'] = "en";
	// $_POST['name'] = "Two blood units";
	// $_POST['description'] = "Blood Type A + at Rafidia Hospital";

	if(isset($_POST['platform_categories_id']) && isset($_POST['user_id']) && isset($_POST['option'])) {

		require_once 'config.php';
		$platform_categories_id = check_security($_POST['platform_categories_id'], 'string');
        $user_id = check_security($_POST['user_id'], 'string');
        $option = check_security($_POST['option'], 'string');

        switch ($option) {
            case 'en':
                if(!empty(isset($_POST['name'])) && !empty(isset($_POST['description']))) {
                    require_once 'translation.php';
                    $name_en = check_security($_POST['name'], 'string');
                    $name_ar = Translation('en', 'ar', $name_en);
                    $description_en = check_security($_POST['description'], 'string'); 
                    $description_ar = Translation('en', 'ar', $description_en);  
                    echo  assistance($platform_categories_id, $user_id, $name_en, $name_ar, $description_en, $description_ar);
                }
                break;
            case 'ar':
                if(!empty(isset($_POST['name'])) && !empty(isset($_POST['description']))) {
                    require_once 'translation.php';
                    $name_ar = check_security($_POST['name'], 'string');
                    $name_en = Translation('ar', 'en', $name_ar);
                    $description_ar = check_security($_POST['description'], 'string'); 
                    $description_en = Translation('ar', 'en', $description_ar);  
                    echo assistance($platform_categories_id, $user_id, $name_en, $name_ar, $description_en, $description_ar);
                }
                break;
            
            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }
	} else {

		$response['result'] = "failed";
		$response['code'] = 2;
		$response['alert_message'] = "The input is not parameters";

		die(json_encode($response));exit;
	}

	function assistance($platform_categories_id, $user_id, $name_en, $name_ar, $description_en, $description_ar){
	
        $assistance['user_id'] = $user_id;
        $assistance['platform_categories_id'] = $platform_categories_id;
        $assistance['name_en'] = $name_en;
        $assistance['name_ar'] = $name_ar;
        $assistance['description_en'] = $description_en;
        $assistance['description_ar'] = $description_ar;
      

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = $assistance;

        die(json_encode($response));exit;
	}
?>	