<?php

foreach ($users_array as $user) {
   // var_dump($user);
    $all_docs = $conn->getDocuments('chatapp');
    $found = 0;
    foreach ($all_docs['rows'] as $row) {
        if (is_numeric($row['id'])) { //avem mesaj
            $message = $conn->getDocument('chatapp', $row['id']);
            if (($user['unique_id'] == $message['incoming_msg_id'] || $message['outgoing_msg_id'] == $user['unique_id'])
                && ($message['outgoing_msg_id'] == $outgoing_id || $message['incoming_msg_id'] == $outgoing_id)
            ) {
                $result = $message;
                $found = 1;
            }
        }
    }
    if ($found) {
        if (strlen($result['msg']) > 28)
            $msg = substr($result['msg'], 0, 28) . '...';
        else
            $msg = $result['msg'];
    } else $msg = 'No messages available';
    if (isset($result['outgoing_msg_id'])) {
        ($outgoing_id == $result['outgoing_msg_id']) ? $you = "You: " : $you = "";
    } else {
        $you = "";
    }

    ($user['status'] == "Offline now") ? $offline = "offline" : $offline = "";
    ($outgoing_id == $user['unique_id']) ? $hid_me = "hide" : $hid_me = "";

    $output .= '<a href="chat.php?user_id=' . $user['unique_id'] . '">
                    <div class="content">
                    
                    <div class="details">
                        <span>' . $user['fname'] . " " . $user['lname'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                    <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
}
