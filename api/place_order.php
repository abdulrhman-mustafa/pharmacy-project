<?php
session_start();
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = $data['user_id'] ?? null;
    $total_price = $data['total_price'] ?? 0;
    $name = $data['name'] ?? '';
    $address = $data['address'] ?? '';
    $email = $data['email'] ?? '';
    $cart_items = $data['cart_items'] ?? [];

    if (!$user_id || !$total_price || !$name || !$address || !$email || empty($cart_items)) {
        $response['message'] = 'Missing required fields';
        echo json_encode($response);
        exit();
    }

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, name, address, email, status, created_at) VALUES (:user_id, :total_price, :name, :address, :email, 'pending', NOW())");
        $stmt->execute([
            'user_id' => $user_id,
            'total_price' => $total_price,
            'name' => $name,
            'address' => $address,
            'email' => $email
        ]);
        $order_id = $pdo->lastInsertId();

        // Insert cart items into cart_items table
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity, created_at) VALUES (:user_id, :product_id, :quantity, NOW())");
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity']
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Order placed successfully';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log("Database error: " . $e->getMessage(), 3, "../errors.log");
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit();
?>