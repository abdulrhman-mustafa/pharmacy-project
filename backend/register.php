<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../frontend/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <section class="register">
        <div class="container">
            <div class="image">
                <img src="../frontend/assets/images/Nav-logo.png" alt="logo">
                <h1>Welcome to <span>Hend AbdelFattah's</span> Pharmacy</h1>
            </div>
            <div class="form">
                <h1 class="form-title">Register</h1>
                <form action="user-account.php" method="POST">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" id="name" placeholder="Name" required>
                        <?php
                        if (isset($_SESSION['errors']['name'])) {
                            echo "<div class='error'><p>{$_SESSION['errors']['name']}</p></div>";
                        }
                        ?>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                        <?php
                        if (isset($_SESSION['errors']['email'])) {
                            echo "<div class='error'><p>{$_SESSION['errors']['email']}</p></div>";
                        }
                        ?>
                    </div>
                    <div class="input-group password">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <i class="fa fa-eye" id="eye"></i>
                        <?php
                        if (isset($_SESSION['errors']['password'])) {
                            echo "<div class='error'><p>{$_SESSION['errors']['password']}</p></div>";
                        }
                        ?>
                    </div>
                    <div class="input-group password">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                        <i class="fa fa-eye" id="eye-confirm"></i>
                        <?php
                        if (isset($_SESSION['errors']['confirm_password'])) {
                            echo "<div class='error'><p>{$_SESSION['errors']['confirm_password']}</p></div>";
                        }
                        ?>
                    </div>
                    <?php
                    if (isset($_SESSION['errors']['user_exist']) || isset($_SESSION['errors']['database'])) {
                        $general_error = $_SESSION['errors']['user_exist'] ?? $_SESSION['errors']['database'];
                        echo "<div class='error-main'><p>$general_error</p></div>";
                    }
                    ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
                    <input type="submit" class="btn" value="Sign Up" name="signup">
                </form>
                <p class="or">
                    ----------- or -----------
                </p>
                <div class="icons">
                    <i class="fab fa-facebook"></i>
                    <i class="fa-brands fa-x-twitter"></i>
                    <i class="fab fa-google"></i>
                </div>
                <div class="links">
                    <p>Already have an account?</p>
                    <a href="form.php">Sign In</a>
                </div>
            </div>
        </div>
    </section>
    <script src="../frontend/assets/js/form.js"></script>
</body>
</html>
<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>