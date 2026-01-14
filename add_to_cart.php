<?php
include_once 'dbconnect.php';

header('Content-Type: application/json');

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

if ($user_id === null || $user_id === false || $product_id === null || $product_id === false) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid user_id or product_id']);
    exit;
}

try {
    $check = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND user_id = ?");
    if (!$check) throw new Exception($conn->error);
    $check->bind_param("ii", $product_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows > 0) {
        $stat = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = ? AND user_id = ?");
        if (!$stat) throw new Exception($conn->error);
        $stat->bind_param("ii", $product_id, $user_id);
    } else {
        $stat = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        if (!$stat) throw new Exception($conn->error);
        $stat->bind_param("ii", $user_id, $product_id);
    }

    if ($stat->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stat->error]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

?>