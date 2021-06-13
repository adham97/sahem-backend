<?php

    // $_POST['token_id'] = 'aEpUazdjOHNIOCEkMDgvMDYvMjEgMDQ6MTM6MjAhJGRpbWFAZ21haWwuY29tISQxOTIuMTY4LjEuMTUz';
    // $_POST['role'] = '3';
    // $_POST['user_id'] = '5';
    // $_POST['count'] = 'count';

    if( !empty(isset($_POST['token_id'])) ) {
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();
   
        if( !empty(isset($_POST['notifications_id'])) ) {
            require_once 'helper.php';

            $notification_id = check_security($_POST['notifications_id'], 'string');

            updateNotification($notification_id);

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = "Update notification successfully";

            die(json_encode($response));exit;
        }

        if( !empty(isset($_POST['role'])) && !empty(isset($_POST['user_id'])) && !empty(isset($_POST['count'])) ) {
            require_once 'helper.php';

            $role = check_security($_POST['role'], 'string');
            $user_id = check_security($_POST['user_id'], 'string');
            
            if($role == '2')
                $role = '1';

            switch ($role) {
                case '1':
                    $count = countNotificationManager();
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $count;

                    die(json_encode($response));exit;
                    break;
                
                case '3':
                    $count = countNotificationUser($user_id);
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $count;

                    die(json_encode($response));exit;
                    break;
            
                case '4':
                    $count = countNotificationDriver();
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $count;

                    die(json_encode($response));exit;
                    break;
    
                default:
                    # code...
                    break;
            }
        }

        if( !empty(isset($_POST['role'])) && !empty(isset($_POST['user_id'])) ) {
            require_once 'helper.php';

            $role = check_security($_POST['role'], 'string');
            $user_id = check_security($_POST['user_id'], 'string');
            
            if($role == '2')
                $role = '1';

            switch ($role) {
                case '1':
                    $notifications = getNotificationManager();
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $notifications;

                    die(json_encode($response));exit;
                    break;
            
                case '3':
                    $notifications = getNotificationUser($user_id);
                    
                    $response['result'] = "success";
                    $response['code'] = 1;
                    $response['message'] = $notifications;

                    die(json_encode($response));exit;
                    break;
        
                case '4':
                    # code...
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
?>