<?php
session_start();
include_once "config.php";

$outgoing_id = $_SESSION['unique_id'];
$searchTerm = str_replace("'", "''", $_POST['searchTerm']);
$all_docs = $conn->getDocuments('chatapp');
$found = 0;
$output = '';
$users_array = array();
foreach ($all_docs['rows'] as $row) {
    if (!is_numeric($row['id'])) {
        $user = $conn->getDocument('chatapp', $row['id']);
        if ($user['unique_id'] != $outgoing_id && (str_contains($user['fname'], $searchTerm) || (str_contains($user['lname'], $searchTerm)))) {
            array_push($users_array, $user);
        }
    }
}
if (empty($users_array))
    $output .=  'No user found related to your search term';
else
    include_once "data.php";
echo $output;
