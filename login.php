<?php
include_once 'dbconnect.php';

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo "not_found";
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

$stat = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stat->bind_param("s", $username);
$stat->execute();
$result = $stat->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        echo "success";
    } else {
        echo "wrong";
    }
} else {
    echo "not_found";
}
