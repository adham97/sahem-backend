<?php
    require_once 'config.php';
    
    function selectLastMessage($header_id) {
        global $conn;
                
        $sql_select_message = "SELECT * FROM  message 
                            WHERE header_id = :header_id
                            ORDER BY message_id DESC LIMIT 1";

        try {
            $stmt_message = $conn->prepare($sql_select_message);
            $stmt_message->bindParam(':header_id', $header_id, PDO::PARAM_STR);
            $stmt_message->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $message_info = $stmt_message->fetch();

        if($message_info && $stmt_message->rowCount() == 1) {
            $message['message_id'] = $message_info['message_id'];
            $message['message_type'] = $message_info['message_type'];
            $message['content'] = $message_info['content'];
            $message['time'] = $message_info['time'];

            return $message;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there Message.";

            die(json_encode($response));exit;
        }
    }
?>