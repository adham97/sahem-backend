<?php
    // $_POST['user_id'] = '4';
    // $_POST['token_id'] = 'aXc2NXgwdkRNZCEkMjEvMDUvMjEgMTI6MDM6MDAhJDA1Njg3NjYxMjUhJDE5Mi4xNjguMS4xNTM=';
    // $_POST['is_from_sender'] = '0'; 
    // $_POST['content'] = 'Hi'; 
    // $_POST['message_type'] = '1';
    // $_POST['type'] = '2';

    if (empty(isset($_POST['user_id'])) && empty(isset($_POST['token_id']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $user_id = check_security($_POST['user_id'], 'string');
        if(!empty(isset($_POST['is_from_sender'])) && !empty(isset($_POST['content'])) && !empty(isset($_POST['message_type']))) {
            $is_from_sender = check_security($_POST['is_from_sender'], 'string');
            $content = check_security($_POST['content'], 'string');
            $message_type = check_security($_POST['message_type'], 'string');
            sendMessage($user_id, $is_from_sender, $content, $message_type);
        } else {
            getMessages($user_id);
        }
    }

    function sendMessage($from_user_id, $is_from_sender, $content, $message_type) {
        global $conn;

        $header = isHeader($from_user_id);
        if(empty($header['exist'])) {
            $header['header_id'] = insertHeader($from_user_id);
        }
        
        if (!empty(isset($_POST['type'])) && $_POST['type'] != '0') {
            $type = check_security($_POST['type'], 'string');
            updateMessage($type, $header['header_id']);
        }

        $sql_insert_message = "INSERT INTO `message`(`header_id`, `is_from_sender`, `content`, `message_type`) 
        VALUES (:header_id, :is_from_sender, :content, :message_type)";

        try{
            $stmt_insert_message = $conn->prepare($sql_insert_message);
            $stmt_insert_message->bindParam(':header_id', $header['header_id'], PDO::PARAM_STR);
            $stmt_insert_message->bindParam(':is_from_sender', $is_from_sender, PDO::PARAM_STR);
            $stmt_insert_message->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt_insert_message->bindparam(':message_type', $message_type, PDO::PARAM_STR); 
            $stmt_insert_message->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $sql_select_message = "SELECT * FROM  message m, header h
                               WHERE m.header_id = :header_id
                               AND   m.header_id = h.header_id
                               ORDER BY message_id DESC LIMIT 1";

        try{
            $stmt_select_message = $conn->prepare($sql_select_message);
            $stmt_select_message->bindParam(':header_id', $header['header_id'], PDO::PARAM_STR);
            $stmt_select_message->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $message_info = $stmt_select_message->fetch();

        if($message_info && $stmt_select_message->rowCount() >= 1){
            $message['from_user_id'] = $message_info['from_user_id'];
            $message['is_from_sender'] = $message_info['is_from_sender'];				
            $message['content'] = $message_info['content'];
            $message['message_type'] = $message_info['message_type'];
            $message['time'] = $message_info['time'];
    
            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $message;
    
            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there Message.";

            die(json_encode($response));exit;
        }
    }

    function updateMessage($type, $header_id) {
        global $conn;

        require_once 'select_message.php';
        $message = selectLastMessage($header_id);
        $sql_update_type = "UPDATE  message
                            SET   message_type = :message_type
                            WHERE message_id = :message_id
                            AND   header_id = :header_id";

        try {
            $stmt_type = $conn->prepare($sql_update_type);
            $stmt_type->bindParam(':message_type', $type, PDO::PARAM_STR);
            $stmt_type->bindParam(':message_id', $message['message_id'], PDO::PARAM_STR);
            $stmt_type->bindParam(':header_id', $header_id, PDO::PARAM_STR);
            $stmt_type->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }
    }

    function getMessages($user_id) {
        global $conn;

        $sql_select_messages = "SELECT * FROM  header h, message m 
                                WHERE h.header_id = m.header_id
                                AND   h.from_user_id = :user_id";

        try {
            $stmt_messages = $conn->prepare($sql_select_messages);
            $stmt_messages->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_messages->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $messages_info = $stmt_messages->fetchAll(PDO::FETCH_OBJ);
        $messages = array();

        if($messages_info && $stmt_messages->rowCount() >= 1){
            foreach($messages_info as $message){
                $body['from_user_id'] = $message->from_user_id;
                $body['is_from_sender'] = $message->is_from_sender;				
                $body['content'] = $message->content;
                $body['message_type'] = $message->message_type;
                $body['time'] = $message->time;
                
                array_push($messages, $body);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $messages;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there Message.";

            die(json_encode($response));exit;
        }
    }

    function isHeader($from_user_id) {
        global $conn;

        $sql_select_header = "SELECT * FROM header WHERE from_user_id = :from_user_id";

        try{
            $stmt_select_header = $conn->prepare($sql_select_header);
            $stmt_select_header->bindParam(':from_user_id', $from_user_id, PDO::PARAM_STR);
            $stmt_select_header->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $header_info = $stmt_select_header->fetch();
        
        if($header_info && $stmt_select_header->rowCount() == 1){
            $sql_update_header = "UPDATE header SET time = :time WHERE from_user_id = :from_user_id";
            $date = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('Asia/Jerusalem'));

            try{
                $stmt_update_header = $conn->prepare($sql_update_header);
                $stmt_update_header->bindParam(':from_user_id', $from_user_id, PDO::PARAM_STR);
                $stmt_update_header->bindParam(':time', $time, PDO::PARAM_STR);
                $stmt_update_header->execute();

            }catch (PDOException $ex) {	

                $response['result'] = "failed";
                $response['code'] = 3;
                $response['alert_message'] = "error DB: ".$ex;

                die(json_encode($response));exit;
            }

            $header['header_id'] = $header_info['header_id'];
            $header['exist'] = true;
            return $header;
        } 
    }

    function insertHeader($from_user_id) {
        global $conn;

        $sql_insert_header = "INSERT INTO `header`(`from_user_id`) 
        VALUES (:from_user_id)";

        try{
            $stmt_insert_header = $conn->prepare($sql_insert_header);
            $stmt_insert_header->bindParam(':from_user_id', $from_user_id, PDO::PARAM_STR);
            $stmt_insert_header->execute();

        }catch (PDOException $ex) {	

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        return $conn->lastInsertId();
    }
?>