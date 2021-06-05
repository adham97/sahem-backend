<?php

    // $_POST['user_role'] = '1';
    // $_POST['token_id'] = 'cHdDZTNYb3lQbCEkMDIvMDYvMjEgMDg6MDE6MDYhJDA1Njg3NjYxMjUhJDE5Mi4xNjguMS4xNTM=';
    // $_POST['option'] = 'select';
    // $_POST['user_id'] = '1';
    // $_POST['role'] = '3';

    if (empty(isset($_POST['token_id'])) && empty(isset($_POST['user_id'])) && empty(isset($_POST['option']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $user_id = check_security($_POST['user_id'], 'string');
        $option = check_security($_POST['option'], 'string');

        switch ($option) {
            case 'select':
                selectCard($user_id);
                break;
            
            case 'insert':
                insertCard();
                break;

            case 'get_card':
                getCard($user_id);
                break;

            default:
                $response['result'] = "failed";
                $response['code'] = 6;
                $response['alert_message'] = "These privacy shortcuts are confidential";

                die(json_encode($response));exit;
                break;
        }       
    }

    function selectCard($user_id) { 
        global $conn;

        $sql_select_cards = "SELECT * FROM  cards WHERE user_id = :user_id";

        try {
            $stmt_cards = $conn->prepare($sql_select_cards);
            $stmt_cards->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_cards->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $cards_info = $stmt_cards->fetchAll(PDO::FETCH_OBJ);
        $cards = array();

        if($cards_info && $stmt_cards->rowCount() >= 1){
            foreach($cards_info as $card){
                $message['card_id'] = $card->card_id;				
                $message['user_id'] = $card->user_id;
                $message['card_number'] = $card->card_number;
                $message['expiry_date'] = $card->expiry_date;
                $message['card_holder_name'] = $card->card_holder_name;
                $message['cvv_code'] = $card->cvv_code;
                $message['amount'] = $card->amount;
                
                array_push($cards, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $cards;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there card";

            die(json_encode($response));exit;
        }
    }

    function insertCard() {
        global $conn;

        if(!empty(isset($_POST['card']))) {

            $cards = json_decode($_POST['card']);

            foreach ($cards as $card) 
                $array[] = $card;
    
            $user_id  = check_security($array[0], 'string');
            $card_number = check_security($array[1], 'string');
            $expiry_date = check_security($array[2], 'string');
            $card_holder_name = check_security($array[3], 'string');
            $cvv_code = check_security($array[4], 'string');
            $amount = check_security($array[5], 'string');
            
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
            
            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = "Insert Card Done";
    
            die(json_encode($response));exit;
        }
   }
   
   function getCard($user_id) { 
        global $conn;

        $sql_select_card = "SELECT * FROM  cards WHERE user_id = :user_id ORDER BY card_id DESC LIMIT 1";

        try {
            $stmt_card = $conn->prepare($sql_select_card);
            $stmt_card->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_card->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $card_info = $stmt_card->fetch();

        if($card_info && $stmt_card->rowCount() == 1){
            $card_id = $card_info['card_id'];

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $card_id;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['message'] = 0;

            die(json_encode($response));exit;
        }
    }
?>