<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../api/user-account.php'); // تحويل لصفحة تسجيل الدخول
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Render All Elements Normally -->
    <link rel="stylesheet" href="../assets/css/normalize.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <!-- Font Awesome Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Main Template CSS File -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="../assets/images/Nav-logo.png" type="image/x-icon">
    <title>Hend Abdelfattah's Pharmacy</title>
</head>
<body>
    <!-- Btn-scroll -->
    <button id="btn-scroll">
        <i class="fa-solid fa-turn-up"></i>
    </button>
    <!-- Start Sticky-Cart -->
    <div class="sticky-cart">
        <li class="hide-from-mobile">
            <a class="cart-icon" href="../pages/cart.php">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-count"></span>
            </a>
        </li>
    </div>
    <!-- End Sticky-Cart -->

    <!-- Start Header (Included from separate file) -->
    <?php include '../includes/header.php'; ?>

    <!-- Start Home -->
    <section class="home" id="home">
        <div class="overlay"></div>
        <div class="container">
            <div class="content">
                <h1>Greetings! <br>
                Welcome to <span>Hend AbdelFattah's</span> Pharmacy, we Care for Your Health.</h1>
                <p>Your Health, Our Priority – Trusted Care, Expert Advice, and Quality Products at Your Fingertips</p>
                <div class="buttonss">
                    <button class="btnn">Get Started</button>
                    <button class="btnn">Learn More</button>
                </div>
            </div>
        </div>
    </section>
    <!-- End Home -->

    <!-- About Start -->
    <div class="about" id="about">
        <div class="container">
            <div class="heading">
                <h2>About Us</h2>
                <p>We Care For Your Health</p>
            </div>
            <div class="about-content">
                <div class="image">
                    <img src="../assets/images/about.jpeg" alt="about" />
                </div>
                <div class="text">
                    <p>
                        At Hend Abdelfattah's Pharmacy, we are dedicated to providing high-quality pharmaceutical products, expert advice, and compassionate care to support your health journey. Whether you're picking up prescriptions, seeking over-the-counter solutions, or looking for wellness tips, we're here to ensure you get the care you deserve.
                    </p>
                    <hr />
                    <p>
                        <b>Why To Choose Us</b> <br> To improve lives by making healthcare accessible, affordable, and reliable. We aim to be more than a pharmacy; we're a community-focused resource for your health needs.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Start Services -->
    <section class="services" id="services">
        <div class="container">
            <div class="heading">
                <h2>Services</h2>
                <p>At Hend Abdelfattah's Pharmacy, we offer a wide range of services to meet your healthcare needs.</p>
            </div>
            <div class="services-content">
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="content">
                        <h3>Online Pharmacy Consultation</h3>
                        <p>Represents a pharmacist or medical professional providing advice.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="content">
                        <h3>Home Medication Delivery</h3>
                        <p>Symbolizes delivery or transportation.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <div class="content">
                        <h3>Prescription Management</h3>
                        <p>Indicates handling prescriptions or medication records.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="content">
                        <h3>Basic Health Screening</h3>
                        <p>Represents health checks like blood pressure or glucose testing.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-calendar-check"></i>>
                    </div>
                    <div class="content">
                        <h3>Chronic Medication Follow-Up</h3>
                        <p>Suggests scheduling and reminders for medication adherence.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="content">
                        <h3>Vaccination Services</h3>
                        <p>Directly relates to injections or vaccinations.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="content">
                        <h3>Online Pharmacy Consultation</h3>
                        <p>Represents vitamins or supplements.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="content">
                        <h3>Skincare Services</h3>
                        <p>Evokes beauty or skincare treatments.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-smoking-ban"></i>
                    </div>
                    <div class="content">
                        <h3>Smoking Cessation Program</h3>
                        <p>Clearly indicates quitting smoking.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="content">
                        <h3>Medication Packaging</h3>
                        <p>Suggests organizing or packaging medications.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="content">
                        <h3>Nutritional Counseling</h3>
                        <p>Represents food or dietary advice.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="icons">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <div class="content">
                        <h3>Emergency Medication Service</h3>
                        <p>Symbolizes urgency or emergency support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Services -->

    <!-- Start Products -->
    <section class="products" id="products">
        <div class="container">
            <div class="heading">
                <h2>Products</h2>
                <p>Order now from our pharmacy</p>
                <div class="menu-buttons">
                    <button class="filter-btn active" data-category="all">All</button>
                    <button class="filter-btn" data-category="cosmetics">Cosmetics</button>
                    <button class="filter-btn" data-category="regular">Regular</button>
                    <button class="filter-btn" data-category="controlled">Controlled Medicines</button>
                </div>
            </div>
            <div class="box-holder">
                <!-- products in database -->
            </div>
            <div class="buttons">
                <button>See More</button>
            </div>
        </div>
    </section>
    <!-- End Products -->

    <!-- Start News-letter -->
    <section class="news" id="news">
        <div class="container">
            <div class="heading">
                <h2>News Letter</h2>
                <p>Always Stay Up-To-Date</p>
            </div>
            <div class="cards">
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med11.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Super Focus Capsules</h3>
                        <p>These supplements aim to support brain health, improve mental clarity, and boost focus. They are enriched with vitamins and minerals to enhance energy and cognitive performance.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med13.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Keto + AC Gummies</h3>
                        <p>These are dietary supplements formulated with apple cider vinegar and keto salts. They are designed to support ketosis, weight loss, and metabolism, often used as part of a ketogenic diet.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med9.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>The purpler Magic</h3>
                        <p>These supplements aim to support brain health, improve mental clarity, and boost focus.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med8.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Keto + AC</h3>
                        <p>fatty acids are healthy fats that support your brain, heart, and joints. They reduce inflammation and are found in foods like fish, flaxseeds, and walnuts.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med7.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Hagad Defend</h3>
                        <p>A key For antioxidant that protects cells from free radical damage, supports healthy skin, eyes, and immune function, and aids in wound healing and inflammation reduction</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med6.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>The purpler Magic</h3>
                        <p>These supplements aim to support brain health, improve mental clarity, and boost focus.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med5.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Body Magic</h3>
                        <p>medicine with paracetamol that helps reduce pain and fever, commonly used for headaches, colds, and body aches.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med4.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Omega-3</h3>
                        <p>fatty acids are healthy fats that support your brain, heart, and joints. They reduce inflammation and are found in foods like fish, flaxseeds, and walnuts.</p>
                    </div>
                </div>
                <div class="card">
                    <div class="image">
                        <img src="../assets/images/med3.jpeg" alt="box1" />
                    </div>
                    <div class="info">
                        <h3>Vitamen E</h3>
                        <p>A key For antioxidant that protects cells from free radical damage, supports healthy skin, eyes, and immune function, and aids in wound healing and inflammation reduction. It’s commonly found in nuts, seeds, and vegetable oils.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End News-letter -->

    <!-- Contact Start -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="heading">
                <h2>Contact Us</h2>
                <p>In Your Service 24 Hours, 7 Days a Week</p>
            </div>
            <div class="content">
                <form action="">
                    <input type="text" class="main-input" name="name" placeholder="Your Name" />
                    <input type="email" class="main-input" name="mail" placeholder="Your Email" />
                    <textarea class="main-input" name="message" placeholder="Your Message"></textarea>
                    <input type="submit" value="Send Message" />
                </form>
                <div class="info">
                    <h4>Get In Touch</h4>
                    <span class="phone">+020 11 223 3445</span>
                    <span class="phone">+020 11 223 3445</span>
                    <h4>Where We Are</h4>
                    <address>
                        1200, New Borg El Arab<br />Alexandria, Egypt<br />+020 11 223 3445<br />
                    </address>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact End -->

    <!-- Start Footer (Included from separate file) -->
    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/products.js"></script>
</body>
</html>