<?php 
session_start();
require_once('../private/Database.php');

$db = new Database();



 //cart items
 if(!empty($_SESSION['id'])){
  $carts = $db->getCartByUser($_SESSION['id']);
 }
  //cart total price
  $cartTotal = 0;
  if(!empty($carts)){
  foreach($carts as $cart){
      $cartTotal +=  $cart->price;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //logout user
    if(isset($_POST['logout'])){
     if($_POST['logout'] == 'logout_user'){
         session_destroy();
        }
     }

     //login using modal
     if(isset($_POST['email']) && isset($_POST['password'])){
     $email =  filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
     $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);

     if($db->checkUserActive($email)){
        $response->error("Your Account Has Been Temporarily Suspended!Please Contact Admin.");
        exit;
    }

     if($db->validateUserEmail($email)){
         $userDetails = $db->userDetails($email);

         if(password_verify($password,$userDetails->password)){
             $_SESSION['name'] = $userDetails->name;
             $_SESSION['id'] = $userDetails->id;
             $_SESSION['email'] = $userDetails->email;
             $response->success("Login Success");
             exit;
         }else{
             $response->error("Invalid login Credentials!");
             exit;
         }

     }else{
         $response->error("Email Not Found!Please Sign Up.");
         exit;
     }

     }

     //contact
     if(isset($_POST['contact']) && $_POST['contact'] == 'contact'){
        $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'],FILTER_SANITIZE_STRING);
        $company = filter_var($_POST['company'],FILTER_SANITIZE_STRING);
        $message = filter_var($_POST['message'],FILTER_SANITIZE_STRING);

       //save details
        $db->saveContact($name,$email,$subject,$company,$message);
        $success_message = "ThankYou For Getting in Touch with Us.We will get Back to you shortly!";
        
    }
     
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Shop | Contact</title>

    <!-- Font awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- SmartMenus jQuery Bootstrap Addon CSS -->
    <link href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
    <!-- Product view slider -->
    <link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.css">
    <!-- slick slider -->
    <link rel="stylesheet" type="text/css" href="css/slick.css">
    <!-- price picker slider -->
    <link rel="stylesheet" type="text/css" href="css/nouislider.css">
    <!-- Theme color -->
    <link id="switcher" href="css/theme-color/default-theme.css" rel="stylesheet">
    <!-- Top Slider CSS -->
    <link href="css/sequence-theme.modern-slide-in.css" rel="stylesheet" media="all">

    <!-- Main style sheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!-- wpf loader Two -->
    <div id="wpf-loader-two">
        <div class="wpf-loader-two-inner">
            <span>Loading</span>
        </div>
    </div>
    <!-- / wpf loader Two -->
    <!-- SCROLL TOP BUTTON -->
    <a class="scrollToTop" href="#"><i class="fa fa-chevron-up"></i></a>
    <!-- END SCROLL TOP BUTTON -->


    <!-- Start header section -->
    <header id="aa-header">
        <!-- start header top  -->
        <div class="aa-header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-top-area">
                            <!-- start header top left -->
                            <div class="aa-header-top-left">
                                <!-- start language -->
                                <div class="aa-language">
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" type="button" id="dropdownMenu1"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <img src="img/flag/ind.jpg" alt="india flag">INDIA
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="#"><img src="img/flag/ind.jpg" alt="">INDIA</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- / language -->

                                <!-- start currency -->
                                <div class="aa-currency">
                                    <div class="dropdown">

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="#"><i class="fa fa-euro"></i>EURO</a></li>
                                            <li><a href="#"><i class="fa fa-jpy"></i>YEN</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- / currency -->
                                <!-- start cellphone -->
                                <div class="cellphone hidden-xs">
                                    <p><span class="fa fa-phone"></span>+91 7003465016 | +91 8777252070</p>
                                </div>
                                <!-- / cellphone -->
                            </div>
                            <!-- / header top left -->
                            <div class="aa-header-top-right">
                                <ul class="aa-head-top-nav-right">
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <li><i class="fa fa-user" aria-hidden="true"></i> <a
                                            href="user_profile.php"><?php echo $_SESSION['name']; ?></a></li>
                                    <?php endif; ?>
                                    <?php if(!isset($_SESSION['email'])) : ?>
                                    <li><a href="account.php">My Account</a></li>
                                    <?php endif; ?>
                                    <?php if(!isset($_SESSION['email'])) : ?>
                                    <li><a href="seller_register.php">Become A Seller</a></li>
                                    <?php endif; ?>
                                    <li class="hidden-xs"><a href="cart.php">My Cart</a></li>
                                    <li class="hidden-xs"><a href="checkout.php">Checkout</a></li>
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <li><a href="user_order.php">My Orders</a></li>
                                    <?php endif; ?>
                                    <?php if(isset($_SESSION['email'])) : ?>
                                    <li>
                                        <form action="index.php" method="POST">
                                            <input type="submit" value="Logout" class="logout">
                                            <input type="hidden" name="logout" value="logout_user">
                                        </form>
                                    </li>
                                    <?php else: ?>
                                    <li><a href="" data-toggle="modal" data-target="#login-modal">Login</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / header top  -->

        <!-- start header bottom  -->
        <div class="aa-header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-bottom-area">
                            <!-- logo  -->
                            <div class="aa-logo">
                                <!-- Text based logo -->
                                <a href="index.php">
                                    <span class="fa fa-shopping-cart"></span>
                                    <p>daily<strong>Shop</strong> <span>Your Shopping Partner</span></p>
                                </a>
                                <!-- img based logo -->
                                <!-- <a href="index.php"><img src="img/logo.jpg" alt="logo img"></a> -->
                            </div>
                            <!-- / logo  -->
                            <!-- cart box -->
                            <div class="aa-cartbox">
                                <a class="aa-cart-link" href="cart.php">
                                    <span class="fa fa-shopping-basket"></span>
                                    <span class="aa-cart-title">SHOPPING CART</span>
                                    <?php if(!empty($_SESSION['id'])): ?><span
                                        class="aa-cart-notify"><?php  echo $db->getCartCount($_SESSION['id']);  ?></span><?php endif; ?>
                                </a>
                                <div class="aa-cartbox-summary">
                                    <ul>
                                        <?php if(!empty($carts)): ?>
                                        <?php foreach($carts as $cart): ?>
                                        <li>
                                            <a class="aa-cartbox-img" href="#"><img
                                                    src="photos/<?php echo $cart->product_image; ?>" alt="img"></a>
                                            <div class="aa-cartbox-info">
                                                <h4><a href="#"><?php echo $cart->product_name; ?></a></h4>
                                                <p><?php echo $cart->quantity; ?> x <?php echo $cart->price; ?></p>
                                            </div>
                                            <a class="aa-remove-product" href="#"><span class="fa fa-times"></span></a>
                                        </li>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <li>
                                            <span class="aa-cartbox-total-title">
                                                Total
                                            </span>
                                            <span class="aa-cartbox-total-price">
                                                &#8377; <?php echo $cartTotal; ?>
                                            </span>
                                        </li>
                                    </ul>
                                    <?php if(!isset($_SESSION['id'])): ?>
                                    <a class="aa-cartbox-checkout aa-primary-btn"
                                        href="http://localhost/college_ecom/public/account.php">Login</a>
                                    <?php else: ?>
                                    <a class="aa-cartbox-checkout aa-primary-btn"
                                        href="http://localhost/college_ecom/public/checkout.php">Checkout</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- / cart box -->
                            <!-- search box -->
                            <div class="aa-search-box">
                                <form action="">
                                    <input type="text" name="" id="" placeholder="Search here ex. 'man' ">
                                    <button type="submit"><span class="fa fa-search"></span></button>
                                </form>
                            </div>
                            <!-- / search box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / header bottom  -->
    </header>
    <!-- / header section -->
    <!-- menu -->
    <section id="menu">
        <div class="container">
            <div class="menu-area">
                <!-- Navbar -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse">
                        <!-- Left nav -->

                        <ul class="nav navbar-nav">
                            <li><a href="index.php">Home</a></li>

                            <li><a href="http://localhost/college_ecom/public/product.php?id=1">Sports</a></li>
                            <li><a href="http://localhost/college_ecom/public/product.php?id=2">Digital <span
                                        class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="http://localhost/college_ecom/public/product.php?id=2">Camera</a></li>
                                    <li><a href="http://localhost/college_ecom/public/product.php?id=2">Mobile</a></li>
                                    <li><a href="http://localhost/college_ecom/public/product.php?id=2">Tablet</a></li>
                                    <li><a href="http://localhost/college_ecom/public/product.php?id=2">Laptop</a></li>
                                    <li><a href="http://localhost/college_ecom/public/product.php?id=2">Accesories</a>
                                    </li>
                                </ul>
                            </li>


                            <li><a href="contact.php">Contact</a></li>

                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- / menu -->

    <!-- catg header banner section -->
    <section id="aa-catg-head-banner">
        <img src="img/contact_us.png" alt="fashion img" class="create_account_banner">
        <div class="aa-catg-head-banner-area">
            <div class="container">
                <div class="aa-catg-head-banner-content">
                    <h2>Contact</h2>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Contact</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- / catg header banner section -->
    <!-- start contact section -->
    <section id="aa-contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-contact-area">
                        <div class="aa-contact-top">
                            <h2>We are wating to assist you..</h2>
                            <p>24/7 support means customers can get help and find answers to questions as soon as they
                                come up???24/7 and in real-time. </p>
                        </div>
                        <!-- contact map -->
                        <!-- <div class="aa-contact-map">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3902.3714257064535!2d-86.7550931378034!3d34.66757706940219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8862656f8475892d%3A0xf3b1aee5313c9d4d!2sHuntsville%2C+AL+35813%2C+USA!5e0!3m2!1sen!2sbd!4v1445253385137"
                                width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div> -->
                        <div id="map" style="width:100%;height:400px;"></div>
                        <!-- Contact address -->
                        <div class="aa-contact-address">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="aa-contact-address-left">
                                        <form class="comments-form contact-form" action="contact.php" method="POST">
                                            <?php if(!empty($success_message)): ?>
                                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                                            <?php endif; ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" placeholder="Your Name" name="name"
                                                            class="form-control">
                                                        <input type="hidden" name="contact" value="contact">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="email" name="email" placeholder="Email"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" name="subject" placeholder="Subject"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" name="company" placeholder="Company"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <textarea class="form-control" rows="3" placeholder="Message"
                                                    name="message"></textarea>
                                            </div>
                                            <button class="aa-secondary-btn">Send</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="aa-contact-address-right">
                                        <address>
                                            <h4>DailyShop</h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum modi
                                                dolor facilis! Nihil error, eius.</p>
                                            <p><span class="fa fa-home"></span>Kolkata</p>
                                            <p><span class="fa fa-phone"></span>+91 7003465016 | +91 8777252070</p>
                                            <p><span class="fa fa-envelope"></span>Email: dailyshop@gmail.com</p>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscribe section -->
    <section id="aa-subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-subscribe-area">
                        <h3>Subscribe our newsletter </h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex, velit!</p>
                        <form action="" class="aa-subscribe-form">
                            <input type="email" name="" id="" placeholder="Enter your Email">
                            <input type="submit" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Subscribe section -->

    <!-- footer -->
    <footer id="aa-footer">
        <!-- footer bottom -->
        <div class="aa-footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-footer-top-area">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <h3>Main Menu</h3>
                                        <ul class="aa-footer-nav">
                                            <li><a href="#">Home</a></li>
                                            <li><a href="#">Our Services</a></li>
                                            <li><a href="#">Our Products</a></li>
                                            <li><a href="#">About Us</a></li>
                                            <li><a href="#">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Knowledge Base</h3>
                                            <ul class="aa-footer-nav">
                                                <li><a href="#">Delivery</a></li>
                                                <li><a href="#">Returns</a></li>
                                                <li><a href="#">Services</a></li>
                                                <li><a href="#">Discount</a></li>
                                                <li><a href="#">Special Offer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Useful Links</h3>
                                            <ul class="aa-footer-nav">
                                                <li><a href="#">Site Map</a></li>
                                                <li><a href="#">Search</a></li>
                                                <li><a href="#">Advanced Search</a></li>
                                                <li><a href="#">Suppliers</a></li>
                                                <li><a href="#">FAQ</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="aa-footer-widget">
                                        <div class="aa-footer-widget">
                                            <h3>Contact Us</h3>
                                            <address>
                                                <p>Kolkata</p>
                                                <p><span class="fa fa-phone"></span>+91 7003465016 | +91 8777252070</p>
                                                <p><span class="fa fa-envelope"></span>dailyshop@gmail.com</p>
                                            </address>
                                            <div class="aa-footer-social">
                                                <a href="#"><span class="fa fa-facebook"></span></a>
                                                <a href="#"><span class="fa fa-twitter"></span></a>
                                                <a href="#"><span class="fa fa-google-plus"></span></a>
                                                <a href="#"><span class="fa fa-youtube"></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer-bottom -->
        <div class="aa-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-footer-bottom-area">
                            <p>Designed by <a href="http://www.markups.io/">MarkUps.io</a></p>
                            <div class="aa-footer-payment">
                                <span class="fa fa-cc-mastercard"></span>
                                <span class="fa fa-cc-visa"></span>
                                <span class="fa fa-paypal"></span>
                                <span class="fa fa-cc-discover"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- / footer -->
    <!-- Login Modal -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Login or Register</h4>
                    <form class="aa-login-form" id="login_form">
                        <div id="login_err" class="text-danger"></div>
                        <label for=""> Email address<span>*</span></label>
                        <input type="text" placeholder="user@gmail.com" required id="email">
                        <label for="">Password<span>*</span></label>
                        <input type="password" placeholder="Password" id="password" required name="password">
                        <button class="aa-browse-btn" type="submit">Login</button>
                        <label for="rememberme" class="rememberme"><input type="checkbox" id="rememberme"> Remember me
                        </label>
                        <p class="aa-lost-password"><a href="#">Lost your password?</a></p>
                        <div class="aa-register-now">
                            Don't have an account?<a href="account.php">Register now!</a>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="js/jquery.smartmenus.js"></script>
    <!-- SmartMenus jQuery Bootstrap Addon -->
    <script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>
    <!-- To Slider JS -->
    <script src="js/sequence.js"></script>
    <script src="js/sequence-theme.modern-slide-in.js"></script>
    <!-- Product view slider -->
    <script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
    <script type="text/javascript" src="js/jquery.simpleLens.js"></script>
    <!-- slick slider -->
    <script type="text/javascript" src="js/slick.js"></script>
    <!-- Price picker slider -->
    <script type="text/javascript" src="js/nouislider.js"></script>
    <!-- Custom js -->
    <script src="js/custom.js"></script>
    <script src="js/main.js"></script>



    <script>
    let map;

    function initMap() {

        var options = {
            center: {
                lat: 22.5822,
                lng: 88.2345
            },
            zoom: 13,
        }

        map = new google.maps.Map(document.getElementById("map"), options);


        map = new google.maps.Map(document.getElementById("map"), options);

        const marker = new google.maps.Marker({
            position: options.center,
            map: map,
        });
    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoD94OmxmBcp89GtsxRFVz1ccBbSvhkfo&callback=initMap">
    </script>

</body>

</html>