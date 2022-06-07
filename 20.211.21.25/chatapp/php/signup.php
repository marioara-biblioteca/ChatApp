<?php
session_start();
include_once "config.php";
$fname = str_replace("'", "''", $_POST['fname']);
$lname = str_replace("'", "''", $_POST['lname']);
$email = str_replace("'", "''", $_POST['email']);
$password = str_replace("'", "''", $_POST['password']);

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $all_vars = $conn->getDocuments('chatapp');
        $found = 0;
        foreach ($all_vars['rows'] as $row) {
            $user = $conn->getDocument('chatapp', $row['id']);
            if ($user['email'] == $email) {
                $found = 1;
                break;
            }
        }
        if ($found == 0) {
            $new_user = array();
            $id = rand(time(), 100000000);
            $new_user['unique_id'] = strval($id);
            $new_user['id'] = $email;
            $new_user['fname'] = $fname;
            $new_user['lname'] = $lname;
            $new_user['email'] = $email;
            $new_user['password'] = md5($password);
            $new_user['status'] = $status;
            $_SESSION['unique_id'] = $id;
            $result = $conn->createDocument($new_user, 'chatapp', $email);
            echo 'Succes';
        } else {
            echo "This email $email already exists";
        }
    } else {
        echo "$email is not a valid email!";
    }
} else {
    echo "All input fields are required!";
}
