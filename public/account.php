<?php
session_start();
if(!empty($_SESSION['id'])){
    header('location:http://localhost/college_ecom/public/index.php');
}

require_once('../private/Database.php');

$db = new Database();


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['signup']) && $_POST['signup'] == 'signup'){
   
       
        $password_err = '';
        $confirm_password_err = '';
        $aadhaar_err = '';

        //collect form data
        $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['user_email'],FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
        $confirmPassword = filter_var($_POST['confirm_password'],FILTER_SANITIZE_STRING);
        $aadhaar = filter_var($_POST['aadhaar'],FILTER_SANITIZE_NUMBER_INT);

        if(strlen($password) < 6 ){
            $password_err = "Paasword should be minimum 6 characters long!";
            }

            if($password !== $confirmPassword){
            $confirm_password_err = "Password and Confirm password do not match!";
            }

            if(strlen($aadhaar) != 12 ){
            $aadhaar_err = "AADHAAR Number must be 12 digits without any space!";
            
            }

            if($db->validateUserEmail($email)){
            $email_err = "Email Already Exists!";
            
            }

            if(empty($password_err) && empty($aadhaar_err) && empty($confirm_password_err) && empty($email_err)){
                $message = "Account Created Successfully.Kindly Login To proceed!";
                $db->registerUser($name,$aadhaar,$email,$password);
            }

       }

       if(isset($_POST['login']) && $_POST['login'] == 'login'){
        //sanitized form data 
        $email =  filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);

        //error fields
        $password_err = '';
        $email_err = '';
       
       
        
        if($db->validateUserEmail($email)){
            $userDetails = $db->userDetails($email);

            if(password_verify($password,$userDetails->password)){
                $_SESSION['name'] = $userDetails->name;
                $_SESSION['id'] = $userDetails->id;
                $_SESSION['email'] = $userDetails->email;
             
                header('location:http://localhost/college_ecom/public/index.php');
            }else{
                $password_err = "Invalid login Credentials!";
            }

        }else{
            $email_err = "Email Does Not Exists.Please Register!";
        }
    
       }


       //logout user
       if(isset($_POST['logout'])){
        if($_POST['logout'] == 'logout_user'){
            session_destroy();
           }
        }

        //login using modal
        if(isset($_POST['email'])){
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
                $response->success("Login Success",$userDetails);
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
      
}

 
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

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Shop | Account Page</title>

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

    <?php if(isset($message)) : ?>
    <div class="alert alert-success text-center"><?php echo $message; ?></div>
    <?php endif; ?>

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
                                    </p>
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
                                    <?php if(isset($_SESSION['name'])): ?>
                                    <li><a href="" data-toggle="modal" data-target="#login-modal">Logout</a></li>
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
        <img src="img/create_account.png" alt="fashion img" class="create_account_banner">
        <div class="aa-catg-head-banner-area">
            <div class="container">
                <div class="aa-catg-head-banner-content">
                    <h2>Account Page</h2>
                    <ol class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- / catg header banner section -->

    <!-- Cart view section -->
    <section id="aa-myaccount">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-myaccount-area">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="aa-myaccount-login">
                                    <h4>Login</h4>
                                    <form action="account.php" method="POST" class="aa-login-form">

                                        <label for=""> Email address<span>*</span></label>
                                        <input type="text" placeholder="user@gmail.com" required name="email"
                                            class="<?php if(!empty($email_err)) { echo 'is-invalid'; } ?>">
                                        <div class="invalid-feedback text-danger">
                                            <?php if(!empty($email_err)) { echo $email_err; } ?>
                                        </div>
                                        <input type="hidden" value="login" name="login">
                                        <label for="">Password<span>*</span></label>
                                        <input type="password" placeholder="Password" name="password" required
                                            name="password"
                                            class="<?php if(!empty($password_err)) { echo 'is-invalid'; } ?>">
                                        <div class="invalid-feedback text-danger">
                                            <?php  if(!empty($password_err)) { echo $password_err; }  ?>
                                        </div>
                                        <button class="aa-browse-btn" type="submit">Login</button>
                                        <label for="rememberme" class="rememberme"><input type="checkbox"
                                                id="rememberme"> Remember me
                                        </label>
                                        <p class="aa-lost-password"><a href="#">Lost your password?</a></p>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="aa-myaccount-register">
                                    <h4>Sign Up</h4>
                                    <form action="account.php" method="POST" class="aa-login-form">
                                        <div class="form-group">
                                            <label for="">Name<span>*</span></label>
                                            <input type="text" placeholder="Ram Wallia" required name="name">
                                            <input type="hidden" name="signup" value="signup">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email address<span>*</span></label>
                                            <input type="email" placeholder="user@gmail.com" required name="user_email"
                                                class="<?php if(!empty($email_err)) { echo 'is-invalid'; } ?>">
                                            <div class="invalid-feedback text-danger">
                                                <?php if(!empty($email_err)) { echo $email_err; } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">AADHAAR Number<span>*</span></label>
                                            <input type="number" placeholder="user@gmail.com"
                                                class="<?php if(!empty($aadhaar_err)) { echo 'is-invalid'; } ?>"
                                                required name="aadhaar">
                                            <div class="invalid-feedback text-danger">
                                                <?php if(!empty($aadhaar_err)) { echo $aadhaar_err; } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Password<span>*</span></label>
                                            <input type="password" placeholder="***********" required name="password"
                                                class="<?php if(!empty($password_err)) { echo 'is-invalid'; } ?>">
                                            <div class="invalid-feedback text-danger">
                                                <?php  if(!empty($password_err)) { echo $password_err; }  ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Confirm Password<span>*</span></label>
                                            <input type="password" placeholder="**********" required
                                                name="confirm_password"
                                                class="<?php if(!empty($confirm_password_err)) { echo 'is-invalid'; } ?>">
                                            <div class="invalid-feedback text-danger">
                                                <?php if(!empty($confirm_password_err)) { echo $confirm_password_err; }?>
                                            </div>
                                        </div>
                                        <button type="submit" class="aa-browse-btn">Sign Up</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Cart view section -->

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
                                                <p> kolkata</p>
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


</body>

</html>