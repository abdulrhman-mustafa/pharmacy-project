<?php
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name, price, image, category FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // تعديل مسار الصور ليكون متاحًا للواجهة الأمامية
    foreach ($products as &$product) {
        $product['image'] = "../assets/images/" . $product['image'];
    }

    echo json_encode($products);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage(), 3, "../errors.log");
    echo json_encode([]);
}
?>