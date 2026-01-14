<?php
include_once 'dbconnect.php';

if (
    !isset($_POST['nama']) ||
    !isset($_POST['username']) ||
    !isset($_POST['password'])
) {
    echo "invalid";
    exit;
}

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "exists";
} else {
    $stat = $conn->prepare(
        "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)"
    );
    $stat->bind_param("sss", $nama, $username, $password);

    if ($stat->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
