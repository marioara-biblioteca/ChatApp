<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $logout_id = str_replace("'", "''", $_GET['logout_id']);
    if (isset($logout_id)) {
        $status = "Offline now";
        $all_docs = $conn->getDocuments('chatapp');
        foreach ($all_docs['rows'] as $row) {
            if (!is_numeric($row['id'])) {
                $user = $conn->getDocument('chatapp', $row['id']);
                if ($user['unique_id'] == $logout_id) {
                    $user['status'] = $status;
                    $result = $conn->updateDocument($user, 'chatapp', $user['id'], $user['rev']);
                    session_unset();
                    session_destroy();
                    header("location: ../login.php");
                    break;
                }
            }
        }
    } else {
        header("location: ../users.php");
    }
} else {
    header("location: ../login.php");
}
