<?php
    if( !empty(isset($_POST['token_id'])) ) {
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();
   
        if( !empty(isset($_POST['platform_categories_id'])) ) {
            require_once 'helper.php';

            $platform_categories_id = check_security($_POST['platform_categories_id'], 'string');

            $platformCategories = getPlatformCategories($platform_categories_id);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $platformCategories;

            die(json_encode($response));exit;
        }

        if( !empty(isset($_POST['assistance'])) && !empty(isset($_POST['acceptance_id'])) ) {
            require_once 'helper.php';

            $assistance_obj = json_decode($_POST['assistance']);
    
            foreach ($assistance_obj as $element) 
                $assistance_array[] = $element;
    
            $assistance_id = check_security($assistance_array[0], 'string');
            $platform['user_id'] = check_security($assistance_array[1], 'string');
            $platform['platform_categories_id'] = check_security($assistance_array[3], 'string');
            $platform['name_en'] = check_security($assistance_array[4], 'string');
            $platform['name_ar'] = check_security($assistance_array[5], 'string');
            $platform['description_en'] = check_security($assistance_array[6], 'string');
            $platform['description_ar'] = check_security($assistance_array[7], 'string');
       
            $acceptance_id = check_security($_POST['acceptance_id'], 'string');
            
            switch ($acceptance_id) {
                case '2':
                    updateAssistances($assistance_id, $acceptance_id);
                    $platform_id = addPlatform($platform);
                    
                    $notification['user_id'] = $platform['user_id'];
                    $notification['body_id'] = $assistance_id;
                    $notification['title'] = "agreed to request for ". $platform['name_en'];
                    $notification['notification_type_id'] = "2";
                    $notification['status_id'] = "1";
            
                    addNotification($notification);
            
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $platform_id;

                    die(json_encode($response));exit;
                    break;
                
                case '3':
                    updateAssistances($assistance_id, $acceptance_id);
                    
                    $notification['user_id'] = $platform['user_id'];
                    $notification['body_id'] = $assistance_id;
                    $notification['title'] = "refused to request for ". $platform['name_en'];
                    $notification['notification_type_id'] = "3";
                    $notification['status_id'] = "1";
            
                    addNotification($notification);
            
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = "Reques has been rejected";

                    die(json_encode($response));exit;
                    break;
            
                default:
                    $response['result'] = "failed";
                    $response['code'] = 6;
                    $response['alert_message'] = "These privacy shortcuts are confidential";

                    die(json_encode($response));exit;        
                    break;
            }
        }
    }
?>