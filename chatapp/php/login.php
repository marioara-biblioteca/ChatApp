<?php
session_start();
include_once "config.php";
$email = str_replace("'", "\'", $_POST['email']);
$password = str_replace("'", "\'", $_POST['password']);
if (empty($email) == 0 && empty($password) == 0) {
    $result = $conn->getDocuments('chatapp');
    $found = 0;
    foreach ($result['rows'] as $row) {
        if (!is_numeric($row['id'])) {
            $user = $conn->getDocument('chatapp', $row['id']);
            if ($email == $user['email']) {
                $currentUser = $user;
                $found += 1;
            }
        }
    }
    if ($found == 0) {
        echo "$email - This email not Exist!";
    } else if ($found > 1) {
        echo "This can't be happening! Only one user with one email address!";
    } else {
        $user_pass = md5($password);
        $enc_pass = $currentUser['password'];
        if ($user_pass == $enc_pass) {
            $status = "Active now";
            $currentUser['status'] = $status;
            $conn->updateDocument($currentUser, 'chatapp', $currentUser['id'], $user['rev']);
            $_SESSION['unique_id'] = $currentUser['unique_id'];
        } else {
            echo "Email or Password is Incorrect!";
        }
    }
} else {
    echo "All input fields are required!";
}
