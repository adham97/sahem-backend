<?php
    require_once 'config.php';
    require_once 'auth_login.php';
    $user = is_login();

    if(!empty(isset($_POST['question_id']))) { 
        $question_id = check_security($_POST['question_id'], "string");
        $sql_select = "SELECT * FROM answers WHERE answer_id = :question_id";

        try {
            $stmt_answer = $conn->prepare($sql_select);
            $stmt_answer->bindParam(':question_id', $question_id, PDO::PARAM_STR);	
            $stmt_answer->execute();		
            
        }  catch (PDOException $ex) {	
            
            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "Try Agin, error DB: ".$ex;
        
            die(json_encode($response));exit;
        }
    
        $answer_info = $stmt_answer->fetch();
    
        if($answer_info && $stmt_answer->rowCount() == 1) {
            $answer['answer_id'] = $answer_info['answer_id'];
            $answer['answer_en'] = $answer_info['answer_en'];
            $answer['answer_ar'] = $answer_info['answer_ar'];
        
            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $answer;
    
            die(json_encode($response));exit;
        
        } else {
            $response['result'] = "failed";
            $response['code'] = 0;
            $response['alert_message'] = "Try agin, error DB Answer";
    
            die(json_encode($response));exit;
        }
    }

?>