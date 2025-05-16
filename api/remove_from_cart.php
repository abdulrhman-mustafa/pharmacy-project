<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../api/auth.php');
    exit();
}

$user_id = $_SESSION['user']['id'] ?? 1;
$product_id = $_GET['product_id'] ?? null;

if ($product_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        header('Location: ../pages/cart.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>