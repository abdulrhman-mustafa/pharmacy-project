<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../api/auth.php');
    exit();
}
$user = $_SESSION['user'];

// الاتصال بقاعدة البيانات (افترضنا وجود ملف db_connect.php)
require_once '../includes/db_connect.php';

// جلب المنتجات من العربة بناءً على user_id
$user_id = $user['id'] ?? 1; // افتراضي، هيتغير بناءً على بيانات المستخدم
$total_price = 0;
$cart_items = [];

try {
    $stmt = $pdo->prepare("SELECT ci.*, p.name, p.price, p.image FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // حساب الإجمالي
    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// معالجة الدفع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $card = $_POST['card'];
    $cvv = $_POST['cvv'];

    // تخزين الطلب في قاعدة البيانات
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, name, address, email, status, created_at) VALUES (?, ?, ?, ?, ?, 'completed', NOW())");
        $stmt->execute([$user_id, $total_price, $name, $address, $email]);

        // حذف العربة بعد الدفع
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$user_id]);

        // تحويل لعرض رسالة النجاح (يمكن استخدام JavaScript هنا)
        echo "<script>document.getElementById('success-popup').style.display = 'block';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Hend Abdelfattah's Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="../assets/images/Nav-logo.png" type="image/x-icon">
</head>
<body>
    <!-- Start Header -->
    <?php include '../includes/header.php'; ?>

    <section class="cart">
        <div class="container">
            <div class="heading">
                <h2>Your Cart</h2>
                <p>You have <?php echo count($cart_items); ?> items in your cart</p>
            </div>

            <div class="cart-items">
                <?php if (empty($cart_items)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Price: <?php echo htmlspecialchars($item['price']); ?> EGP</p>
                                <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                <p>Subtotal: <?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> EGP</p>
                            </div>
                            <a href="/api/remove_from_cart.php?product_id=<?php echo $item['product_id']; ?>" class="remove-item"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="cart-total">
                <p>Total Price: <span id="total-price"><?php echo number_format($total_price, 2); ?></span> EGP</p>
                <button class="buy-btn">Buy</button>
            </div>

            <!-- Checkout Popup -->
            <div id="checkout-popup" class="popup">
                <div class="popup-content">
                    <h2>Checkout</h2>
                    <form id="payment-form" method="POST">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" required><br>

                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required><br>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required><br>

                        <label for="card">Card Number:</label>
                        <input type="text" id="card" name="card" placeholder="1234 5678 9012 3456" required><br>

                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" required><br>

                        <button type="submit" name="confirm_payment">Confirm Payment</button>
                        <button type="button" id="cancel-checkout">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Success Popup -->
            <div id="success-popup" class="success-popup" style="display: none;">
                <div class="success-content">
                    <div class="checkmark"><i class="fa-solid fa-check"></i></div>
                    <p>Payment successful! Thank you for your purchase!</p>
                    <button id="success-ok-btn" class="success-ok-btn">Ok</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Start Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/cart.js"></script>
</body>
</html>