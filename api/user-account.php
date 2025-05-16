<?php
session_start();
require_once '../includes/db_connect.php';

// Initialize errors array
if (!isset($_SESSION['errors'])) {
    $_SESSION['errors'] = [];
}

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $_SESSION['errors']['csrf'] = 'Invalid request';
    header('Location: ../pages/' . (isset($_POST['signup']) ? 'register.php' : 'form.php'));
    exit();
}

// Handle Signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || strlen($name) < 8) {
        $_SESSION['errors']['name'] = 'Name must be at least 8 characters';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = 'Invalid email format';
    }
    if (strlen($password) < 8) {
        $_SESSION['errors']['password'] = 'Password must be at least 8 characters';
    }
    if ($password !== $confirm_password) {
        $_SESSION['errors']['confirm_password'] = 'Passwords do not match';
    }

    if (empty($_SESSION['errors'])) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['errors']['user_exist'] = 'Email is already registered';
            header('Location: ../pages/register.php');
            exit();
        }
    }

    if (!empty($_SESSION['errors'])) {
        header('Location: ../pages/register.php');
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, :created_at)");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => $created_at
        ]);
        header('Location: ../pages/form.php');
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage(), 3, "../errors.log");
        $_SESSION['errors']['database'] = 'An error occurred, please try again later';
        header('Location: ../pages/register.php');
        exit();
    }
}

// Handle Signin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = 'Invalid email format';
    }
    if (empty($password)) {
        $_SESSION['errors']['password'] = 'Password is required';
    }

    if (!empty($_SESSION['errors'])) {
        header('Location: ../pages/form.php');
        exit();
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'created_at' => $user['created_at']
        ];
        header('Location: ../pages/index.php');
        exit();
    } else {
        $_SESSION['errors']['login'] = 'Invalid email or password';
        header('Location: ../pages/form.php');
        exit();
    }
}

// If accessed directly, redirect to form
header('Location: ../pages/form.php');
exit();
?>