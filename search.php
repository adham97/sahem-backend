<?php
    // $_POST['token_id'] = 'aEpUazdjOHNIOCEkMDgvMDYvMjEgMDQ6MTM6MjAhJGRpbWFAZ21haWwuY29tISQxOTIuMTY4LjEuMTUz';
    // $_POST['search_text'] = "hi";

    if( !empty(isset($_POST['token_id'])) && !empty(isset($_POST['search_text'])) && $_POST['search_text'] != "") {
        require_once "config.php";
        require_once "auth_login.php";
    	$user = is_login();    
    
        $search_text = "%".check_security($_POST['search_text'], 'string')."%";

        $sql_search_platform = "SELECT id FROM platform
                                WHERE  name_en LIKE :search_text 
                                OR     name_ar LIKE :search_text";
    
        try {
            $stmt_platform = $conn->prepare($sql_search_platform);
            $stmt_platform->bindparam(':search_text', $search_text, PDO::PARAM_STR);
            $stmt_platform->execute(); 
    
        } catch (PDOException $ex) {  
            $response['result'] = "failed";
            $response['code'] = 2;
            $response['alert_message'] = "error DB: ".$ex;
    
            die(json_encode($response));exit;
        }			

        $results = array();
        
        $platform_info = $stmt_platform->fetchAll(PDO::FETCH_OBJ);			

        require_once 'helper.php'; 

        if(($platform_info && $stmt_platform->rowCount() >= 1)){
            foreach ($platform_info as $platform) {
                $pla['platform'] = getPlatform($platform->id)['platform'];
                $pla['image'] = getPlatformCategories($pla['platform']['id'])['image'];
               
                array_push($results, $pla);
            }         
        }

        $sql_search_donation = "SELECT donation_store_id FROM donation_store
                                WHERE  name_en LIKE :search_text 
                                OR     name_ar LIKE :search_text";
    
        try {
            $stmt_donation = $conn->prepare($sql_search_donation);
            $stmt_donation->bindparam(':search_text', $search_text, PDO::PARAM_STR);
            $stmt_donation->execute(); 
    
        } catch (PDOException $ex) {  
            $response['result'] = "failed";
            $response['code'] = 2;
            $response['alert_message'] = "error DB: ".$ex;
    
            die(json_encode($response));exit;
        }			
    
        $donation_info = $stmt_donation->fetchAll(PDO::FETCH_OBJ);			

        if(($donation_info && $stmt_donation->rowCount() >= 1)){
            foreach ($donation_info as $donation) {  
                $don['donation'] = getDonation($donation->donation_store_id);
                
                array_push($results, $don);
            }
        }

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = $results;

        die(json_encode($response));exit;
        
    }
?>