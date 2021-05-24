<?php
    require_once 'config.php';
    require_once 'auth_login.php';
    $user = is_login();

    $sql_select = "SELECT * FROM questions";

    try {
        $stmt_question = $conn->prepare($sql_select);
        $stmt_question->execute();		
        
    }  catch (PDOException $ex) {	
        
        $response['result'] = "failed";
        $response['code'] = 3;
        $response['alert_message'] = "Try Agin, error DB: ".$ex;
    
        die(json_encode($response));exit;
    }

    $questions_info = $stmt_question->fetchAll(PDO::FETCH_OBJ);
    $questions = array();

    if($questions_info && $stmt_question->rowCount() >= 1) {
        foreach($questions_info as $question) {

            $message['question_id'] = $question->question_id;
            $message['question_en'] = $question->question_en;
            $message['question_ar'] = $question->question_ar;
        
            array_push($questions, $message);
        }

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = $questions;

        die(json_encode($response));exit;
    
    } else {
        $response['result'] = "failed";
        $response['code'] = 0;
        $response['alert_message'] = "Try agin, error DB Questions";

        die(json_encode($response));exit;
    }
?>