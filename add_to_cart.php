<?php
include_once 'dbconnect.php';
header('Content-Type: application/json');

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if (!$user_id || !$product_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid parameter"
    ]);
    exit;
}

try {
    $check = $conn->prepare(
        "SELECT id FROM cart WHERE user_id = ? AND product_id = ?"
    );
    $check->bind_param("ii", $user_id, $product_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $stat = $conn->prepare(
            "UPDATE cart 
             SET quantity = quantity + 1, is_selected = 1 
             WHERE user_id = ? AND product_id = ?"
        );
        $stat->bind_param("ii", $user_id, $product_id);
    } else {
        $stat = $conn->prepare(
            "INSERT INTO cart (user_id, product_id, quantity, is_selected) 
             VALUES (?, ?, 1, 1)"
        );
        $stat->bind_param("ii", $user_id, $product_id);
    }

    if ($stat->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $stat->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
