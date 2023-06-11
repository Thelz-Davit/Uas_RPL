<?php
session_start();
include('server/connection.php');
include 'server/converttorupiah.php';
if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_photo']);
        header('location: login.php');
        exit;
    }
}

if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['user_email'];

    if ($password !== $confirm_password) {
        header('location: account.php?error=Password did not match');
    } else if (strlen($password) < 6) {
        header('location: account.php?error=Password must be at least 6 characters');

        // Inf no error
    } else {
        $query_change_password = "UPDATE users SET user_password = ? WHERE user_email = ?";

        $stmt_change_password = $conn->prepare($query_change_password);
        $stmt_change_password->bind_param('ss', md5($password), $email);

        if ($stmt_change_password->execute()) {
            header('location: account.php?success=Password has been updated successfully');
        } else {
            header('location: account.php?error=Could not update password');
        }
    }
}

// Get Orders by User Login
if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];

    $query_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";

    $stmt_orders = $conn->prepare($query_orders);
    $stmt_orders->bind_param('i', $user_id);
    $stmt_orders->execute();

    $user_orders = $stmt_orders->get_result();
}

// Cara memperbaiki bug pada $total_bayar saat mengakses halaman account
if (isset($_SESSION['total'])) {
    $total_bayar = $_SESSION['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Swaradana - Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">info@company.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                </div>
                <div>
                    <a class="text-light" href="https://fb.com/templatemo" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->


    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php">
                Swaradana
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="shopping-cart.php">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">7</span>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="account.php">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">+99</span>
                    </a>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Account</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <span>Account</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <form id="account-form" method="POST" action="account.php">
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-info" role="alert">
                                    <?php if (isset($_GET['success'])) {
                                        echo $_GET['success'];
                                    } ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php if (isset($_GET['error'])) {
                                        echo $_GET['error'];
                                    } ?>
                                </div>
                            <?php } ?>
                            <h6 class="checkout__title">Change Password</h6>
                            <div class="checkout__input">
                                <p>Password</p>
                                <input type="password" id="account-password" name="password">
                            </div>
                            <div class="checkout__input">
                                <p>Confirm Password</p>
                                <input type="password" id="account-confirm-password" name="confirm_password">
                            </div>
                            <div class="checkout__input">
                                <input type="submit" class="site-btn" id="change-password-btn" name="change_password" value="CHANGE PASSWORD" />
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?php if (isset($_GET['message'])) { ?>
                            <div class="alert alert-info" role="alert">
                                <?php if (isset($_GET['message'])) {
                                    echo $_GET['message'];
                                } ?>
                            </div>
                        <?php } ?>
                        <div class="checkout__order">
                            <h4 class="order__title">Account Info</h4>
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <img src="<?php echo 'img/profile/' . $_SESSION['user_photo']; ?>" alt="" class="rounded-circle img-responsive" />
                                </div>
                                <div class="col-sm-6 col-md-8">
                                    <h4><?php if (isset($_SESSION['user_name'])) {
                                            echo $_SESSION['user_name'];
                                        } ?></h4>
                                    <small><cite title="Address"><?php if (isset($_SESSION['user_address'])) {
                                                                        echo $_SESSION['user_address'];
                                                                    } ?>, <?php if (isset($_SESSION['user_city'])) {
                                                                                                                                                            echo $_SESSION['user_city'];
                                                                                                                                                        } ?> <i class="fas fa-map-marker-alt"></i></cite></small>
                                    <p>
                                        <i class="fa fa-envelope"></i> <?php if (isset($_SESSION['user_email'])) {
                                                                            echo $_SESSION['user_email'];
                                                                        } ?>
                                        <br />
                                        <i class="fa fa-phone"></i> <?php if (isset($_SESSION['user_phone'])) {
                                                                            echo $_SESSION['user_phone'];
                                                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <h4 class="order__title"></h4>
                            <a href="#orders" class="btn btn-primary">YOUR ORDERS</a>
                            <a href="account.php?logout=1" id="logout-btn" class="btn btn-danger">LOG OUT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Order History Begin -->
    <section id="orders" class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <div class="alert alert-info" role="alert">
                            <?php if (isset($_GET['payment_message'])) {
                                echo $_GET['payment_message'];
                            } ?>
                        </div>
                        <h2>Your Orders History</h2>
                        <span>***</span>
                    </div>
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user_orders as $order) { ?>
                                    <tr>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__text">
                                                <h6><?php echo $order['order_id']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__text">
                                                <?php echo setRupiah($order['order_cost'] * $kurs_dollar); ?>
                                            </div>
                                        </td>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__text">
                                                <h6><?php echo $order['order_status']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="product__cart__item">
                                            <div class="product__cart__item__text">
                                                <h5><?php echo $order['order_date']; ?></h5>
                                            </div>
                                        </td>
                                        <form method="POST" action="order-details.php">
                                            <td class="cart__price">
                                                <input type="hidden" value="<?php echo $order['order_status']; ?>" name="order_status"/>
                                                <input type="hidden" value="<?php echo $order['order_id']; ?>" name="order_id"/>
                                                <input class="btn btn-success" name="order_details_btn" type="submit" value="Details"/>
                                            </td>
                                        </form>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order History End -->

    
    <!-- Start Footer -->
    <footer class="bg-dark" id="tempaltemo_footer">
        <div class="container">
            <div class="row">

            <div class="col-md-4 pt-5">
                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">Swaradana Music Gallery</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            JL. Phh. Mustofa No.23, Neglasari, Kec. Cibeunying Kaler, Kota Bandung, Jawa Barat 40124
                        </li>
                        <li>
                            <i class="fa fa-phone fa-fw"></i>
                            <a class="text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <a class="text-decoration-none" href="mailto:info@company.com">swaradana@gmail.com</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Products</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="#">Alat Musik</a></li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Further Info</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="#">Home</a></li>
                        <li><a class="text-decoration-none" href="#">About Us</a></li>
                        <li><a class="text-decoration-none" href="#">Shop Locations</a></li>
                        <li><a class="text-decoration-none" href="#">FAQs</a></li>
                        <li><a class="text-decoration-none" href="#">Contact</a></li>
                    </ul>
                </div>

            </div>

            <div class="row text-light mb-4">
                <div class="col-12 mb-3">
                    <div class="w-100 my-3 border-top border-light"></div>
                </div>
                <div class="col-auto me-auto">
                    <ul class="list-inline text-left footer-icons">
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a rel="nofollow" class="text-light text-decoration-none" target="_blank" href="http://fb.com/templatemo"><i class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/"><i class="fab fa-instagram fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://twitter.com/"><i class="fab fa-twitter fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.linkedin.com/"><i class="fab fa-linkedin fa-lg fa-fw"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <label class="sr-only" for="subscribeEmail">Email address</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-dark border-light" id="subscribeEmail" placeholder="Email address">
                        <div class="input-group-text btn-success text-light">Subscribe</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100 bg-black py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-12">
                        <p class="text-left text-light">
                            Copyright &copy; 2021 Company Name 
                            | Designed by <a rel="sponsored" href="https://templatemo.com/page/1" target="_blank">TemplateMo</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>