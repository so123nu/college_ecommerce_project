<?php
session_start();
 
require_once('../private/Database.php');
$db=new Database();
$id=$_SESSION['id'];
$findOrdersID=$db->countOrder($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Shop | User Order</title>

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
                                    <p><span class="fa fa-phone"></span>+91 856 956 4526</p>
                                </div>
                                <!-- / cellphone -->
                            </div>
                            <!-- / header top left -->
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
                                    <li class="hidden-xs"><a href="wishlist.php">Wishlist</a></li>
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

                            <li><a href="#">Sports</a></li>
                            <li><a href="#">Digital <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Camera</a></li>
                                    <li><a href="#">Mobile</a></li>
                                    <li><a href="#">Tablet</a></li>
                                    <li><a href="#">Laptop</a></li>
                                    <li><a href="#">Accesories</a></li>
                                </ul>
                            </li>


                            <li><a href="contact.php">Contact</a></li>

                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
    </section>
    <!-- Cart view section -->
    <section id="cart-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="cart-view-area">
                        <h2 class="text-center">Order Details</h2>
                        <div class="cart-view-table">
                            <!--<form action="">-->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order id</th>
                                            <th>Product</th>
                                            <th>Product Price</th>
                                            <th>Quantity</th>
                                            <th>Total Amount</th>
                                            <th>Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($findOrdersID as $record):?>
                                        <tr>
                                            <?php $userOrderTable=$db->userOrder($id,$record->id);?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>">
                                                <strong><?php echo($record->id);?></strong>
                                            </td>
                                            <td><?php echo $userOrderTable[0]->product_name;?></td>
                                            <td><strong>&#8377;<?php echo $userOrderTable[0]->product_price;?></strong>
                                            </td>
                                            <td><strong><?php echo $userOrderTable[0]->quantity;?></strong></td>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>">
                                                <strong>&#8377;<?php echo($record->total_amount);?></strong>
                                            </td>
                                            <?php if($record->order_status=="received"):?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>"><button type="button"
                                                    style="background-color:orange;color:white;padding:4px;border: none;"><?php echo"In Process";?></button>
                                            </td>
                                            <?php elseif($record->order_status=="accepted"):?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>"><button type="button"
                                                    style="background-color=#fff;color:#000;padding:4px;border: none;"><?php echo"Shipped";?></button>
                                            </td>
                                            <?php elseif($record->order_status=="completed"):?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>"><button type="button"
                                                    style="background-color=green;color:white;padding:4px;border: none;"><?php echo"Delivered";?></button>
                                            </td>
                                            <?php elseif($record->order_status=="failed"):?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>"><button type="button"
                                                    style="background-color=green;color:white;padding:4px;border: none;"><?php echo"Failed";?></button>
                                            </td>
                                            <?php elseif($record->order_status=="refund"):?>
                                            <td rowspan="<?php echo(sizeof($userOrderTable));?>"><button type="button"
                                                    style="background-color=green;color:white;padding:4px;border: none;"><?php echo"Refund";?></button>
                                            </td>
                                            <?php endif;?>

                                        </tr>

                                        <?php if(sizeof($userOrderTable)>1):?>
                                        <?php for($index=1;$index<sizeof($userOrderTable);$index++):?>
                                        <tr>
                                            <td><?php echo $userOrderTable[$index]->product_name;?></td>
                                            <td><strong>&#8377;<?php echo $userOrderTable[$index]->product_price;?></strong>
                                            </td>
                                            <td><strong><?php echo $userOrderTable[$index]->quantity;?></strong></td>

                                        </tr>
                                        <?php endfor;?>
                                        <?php endif;?>
                                    </tbody>
                                    <?php endforeach;?>
                                </table>
                            </div>
                            <!--</form>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                                    <p><span class="fa fa-phone"></span>+91 7484858555</p>
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
                        <form class="aa-login-form" action="">
                            <label for="">Username or Email address<span>*</span></label>
                            <input type="text" placeholder="Username or email">
                            <label for="">Password<span>*</span></label>
                            <input type="password" placeholder="Password">
                            <button class="aa-browse-btn" type="submit">Login</button>
                            <label for="rememberme" class="rememberme"><input type="checkbox" id="rememberme"> Remember
                                me </label>
                            <p class="aa-lost-password"><a href="#">Lost your password?</a></p>
                            <div class="aa-register-now">
                                Don't have an account?<a href="account.php">Register Now!</a>
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


</body>

</html>