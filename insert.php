<?php

    include('includes/dbconfig.php');

    $platfrom = 'student';

    $data = [
        'platform' => $platfrom
    ];

    $ref = 'sahem-ea363-default-rtdb/';
    $postdata = $database->getReference($ref)->push($data);

    if($postdata)
        echo "sucess";
    else
        echo "erorr";
?>