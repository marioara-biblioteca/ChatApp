<?php
session_start();
include_once "config.php";
$outgoing_id = $_SESSION['unique_id'];
$output = '';
$all_docs = $conn->getDocuments('chatapp');
$users_array = array();
foreach ($all_docs['rows'] as $row) {
    if (!is_numeric($row['id'])) {
    $user = $conn->getDocument('chatapp', $row['id']);
    if ($user['unique_id'] != $outgoing_id) {
        array_push($users_array, $user);
    }
}
}
if (empty($users_array)) {
    $output .= "No users are available to chat";
} else {
    include_once "data.php";
}
echo $output;
