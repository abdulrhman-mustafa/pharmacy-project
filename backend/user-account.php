<?php
require_once "dbconnect.php";

session_start();
$errors = [];

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $errors['csrf'] = 'Invalid request';
    $_SESSION['errors'] = $errors;
    header('Location: ' . (isset($_POST['signup']) ? 'register.php' : 'form.php'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || strlen($name) < 8) {
        $errors['name'] = 'Name is must be at least 8 characters';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['errors'] = ['user_exist' => 'Email is already registered'];
            header('Location: register.php');
            exit();
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: register.php');
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
        header('Location: form.php');
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage(), 3, "errors.log");
        $_SESSION['errors'] = ['database' => 'An error occurred, please try again later'];
        header('Location: register.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: form.php');
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
        header('Location: home.php');
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
        header('Location: form.php');
        exit();
    }
}
?>