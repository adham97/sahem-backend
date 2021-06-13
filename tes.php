<?php
    require_once 'helper.php';

    $notification['body_id'] = '12';
    $notification['title'] = "send assistance request";
    $notification['notification_type_id'] = "1";
    $notification['status_id'] = "1";

    addNotification($notification);
?>