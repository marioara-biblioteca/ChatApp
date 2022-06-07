<?php

session_start();
print_r($_POST);
print_r($_SESSION);
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = str_replace("'", "\'", $_POST['incoming_id']);
    $message = str_replace("'", "\'", $_POST['message']);
    
    if (!empty($message)) {
	$message_id = $conn->getDocuments('chatapp')['total_rows'];	
        $new_msg = array();
        $new_msg['id'] = strval($message_id);
        $new_msg['incoming_msg_id'] = $incoming_id;
        $new_msg['outgoing_msg_id'] = $outgoing_id;
        $new_msg['msg'] = $message;
        //$new_msg['timestamp'] = time();
        $result = $conn->createDocument($new_msg, 'chatapp',strval($message_id));              

//$message_id += 1;
    }
} else {
    header("location: ../login.php");
}
