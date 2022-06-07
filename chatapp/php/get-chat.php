<?php
session_start();
date_default_timezone_set('UTC');
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = str_replace("'", "\'", $_POST['incoming_id']);
    $output = "";
    $result1 = $conn->getDocuments('chatapp');
    $result2 = $conn->getDocuments('chatapp');
   /* foreach ($result1['rows'] as $row_user) {
        if (!is_numeric($row_user['id'])) {
            $user = $conn->getDocument('chatapp', $row_user['id']);
            foreach ($result2['rows'] as $row_msg) {
	   
                if (is_numeric($row_msg['id'])) {
                    $message = $conn->getDocument('chatapp', $row_msg['id']);
                    if ($user['unique_id'] == $message['outgoing_msg_id']) {
                        if (($message['outgoing_msg_id'] == $outgoing_id && $message['incoming_msg_id'] == $incoming_id)
                            || ($message['outgoing_msg_id'] == $incoming_id && $message['incoming_msg_id'] == $outgoing_id)
                        ) {
	                   
                            if ($message['outgoing_msg_id'] == $outgoing_id) {
                                $output .= '<div class="chat outgoing">
                                <div class="details">
                               
				<p>' . $message['msg'] . '</p>
				    
                                </div>
                                </div>';
                            } else {
                                $output .= '<div class="chat incoming">
                                <div class="details">
                                 
				<p>' . $message['msg'] . '</p>
                                </div>
                                </div>';
                            }
                        }
                    }
                }
            }
        }
    }
*/
    foreach ($result2['rows'] as $row_msg) {
        if (is_numeric($row_msg['id'])) {
            $message = $conn->getDocument('chatapp', $row_msg['id']);
            foreach ($result1['rows'] as $row_user) {
                $user = $conn->getDocument('chatapp', $row_user['id']);
                if ($user['unique_id'] == $message['outgoing_msg_id']) {
                    if (($message['outgoing_msg_id'] == $outgoing_id && $message['incoming_msg_id'] == $incoming_id)
                        || ($message['outgoing_msg_id'] == $incoming_id && $message['incoming_msg_id'] == $outgoing_id)
                    ) {
                        if ($message['outgoing_msg_id'] == $outgoing_id) {
                            $output .= '<div class="chat outgoing">
                            <div class="details">
                            <p>' . $message['msg'] . '</p>
                            </div>
                            </div>';
                        } else {
                            $output .= '<div class="chat incoming">
                            <div class="details">
                            <p>' . $message['msg'] . '</p>
                            </div>
                            </div>';
                        }
                    }
                }
            }
        }
    }
    echo $output;
} else {
    header("location: ../login.php");
}
