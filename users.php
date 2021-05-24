<?php

    // $_POST['user_role'] = '1';
    // $_POST['token_id'] = 'UmdZSFlhSEtOeiEkMjYvMDQvMjEgMDI6MTg6MzMhJDA1Njg3NjYxMjUhJDE5Mi4xNjguMS4xNTg=';
    // $_POST['option'] = 'update';
    // $_POST['user_id'] = '2';
    // $_POST['role'] = '3';

    if (empty(isset($_POST['user_role'])) && empty(isset($_POST['token_id'])) && empty(isset($_POST['option']))) {		
		
        $response['result'] = "failed" ;
		$response['code'] = 4;
		$response['alert_message'] = "Invalid Info";
		
		die(json_encode($response)); exit;
				
	} else { 
        require_once 'config.php';
        require_once 'auth_login.php';
        $user = is_login();

        $user_role = check_security($_POST['user_role'], 'string');
        $option = check_security($_POST['option'], 'string');

        if($user_role == 1 || $user_role == 2){    
            switch ($option) {
                case 'users':
                    getUsers();
                    break;
                
                case 'roles':
                    getRoles();
                    break;

                case 'user':
                    if(!empty(isset($_POST['user_id']))) {
                        $user_id = check_security($_POST['user_id'], 'string');
                        getUser($user_id);
                    }
                    break;

                case 'update':
                    if(!empty(isset($_POST['user_id'])) && !empty(isset($_POST['role']))) {
                        $user_id = check_security($_POST['user_id'], 'string');
                        $role = check_security($_POST['role'], 'string');
                        updateRole($user_id, $role);
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
            $response['code'] = 6;
            $response['alert_message'] = "These privacy shortcuts are confidential";

            die(json_encode($response));exit;
        }  
    }

    function getUsers() { 
        global $conn;

        $sql_select_users = "SELECT * 
                             FROM  users u, roles r
                             WHERE u.user_role = r.id";

        try {
            $stmt_users = $conn->prepare($sql_select_users);
            $stmt_users->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $users_info = $stmt_users->fetchAll(PDO::FETCH_OBJ);
        $users = array();

        if($users_info && $stmt_users->rowCount() >= 1){
            foreach($users_info as $user){
                $message['user_id'] = $user->user_id;				
                $message['first_name'] = $user->first_name;
                $message['father_name'] = $user->father_name;
                $message['grandfather_name'] = $user->grandfather_name;
                $message['last_name'] = $user->last_name;
                $message['email'] = $user->email;
                $message['identify_id'] = $user->identify_id;
                $message['phone'] = $user->phone;
                $message['city'] = $user->city;
                $message['street'] = $user->street;
                $message['role_id'] = $user->user_role;
                $message['role_name'] = $user->name;
                $message['image'] = $user->image_path;
                
                array_push($users, $message);
            }

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $users;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }

    function getRoles() {
        global $conn;

        $sql_select_roles = "SELECT * FROM  roles";

        try {
            $stmt_roles = $conn->prepare($sql_select_roles);
            $stmt_roles->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $roles_info = $stmt_roles->fetchAll(PDO::FETCH_OBJ);
        $roles = array();

        if($roles_info && $stmt_roles->rowCount() >= 1){
            foreach($roles_info as $role){
                $message['id'] = $role->id;				
                $message['name'] = $role->name;
                
                array_push($roles, $message);
            }
            
            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $roles;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there Roles";

            die(json_encode($response));exit;
        }
    }

    function getUser($user_id) {
        global $conn;    

        $sql_select_user = "SELECT *
                            FROM  users u , roles r
                            WHERE user_id = :user_id
                            AND   u.user_role = r.id";

        try {
            $stmt_user = $conn->prepare($sql_select_user);
            $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt_user->execute(); 

        } catch (PDOException $ex) {  

            $response['result'] = "failed";
            $response['code'] = 3;
            $response['alert_message'] = "error DB: ".$ex;

            die(json_encode($response));exit;
        }

        $user_info = $stmt_user->fetch();

        if($user_info && $stmt_user->rowCount() == 1){
            $message['user_id'] = $user_info['user_id'];				
            $message['first_name'] = $user_info['first_name'];
            $message['father_name'] = $user_info['father_name'];
            $message['grandfather_name'] = $user_info['grandfather_name'];
            $message['last_name'] = $user_info['last_name'];
            $message['email'] = $user_info['email'];
            $message['identify_id'] = $user_info['identify_id'];
            $message['phone'] = $user_info['phone'];
            $message['city'] = $user_info['city'];
            $message['street'] = $user_info['street'];
            $message['user_role'] = $user_info['user_role'];
            $message['role_name'] = $user_info['name'];
            $message['image'] = $user_info['image_path'];

            $response['result'] = "success";
            $response['code'] = 1;
            $response['message'] = $message;

            die(json_encode($response));exit;
        } else {
            $response['result'] = "failed";
            $response['code'] = 4;
            $response['alert_message'] = "Not there user";

            die(json_encode($response));exit;
        }
    }

    function updateRole($user_id, $role) {
        global $conn;    

        $sql_update = "UPDATE users SET user_role = :role WHERE user_id = :user_id";

        try {
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt_update->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt_update->execute(); 

        } catch (PDOException $ex) {  

        $response['result'] = "failed";
        $response['code'] = 3;
        $response['alert_message'] = "error DB: ".$ex;

        die(json_encode($response));exit;
        }

        $response['result'] = "success";
        $response['code'] = 1;
        $response['message'] = "Update role done";

        die(json_encode($response));exit;
    }
?>