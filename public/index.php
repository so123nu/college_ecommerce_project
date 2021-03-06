<?php 
session_start();
require_once('../private/Database.php');
require_once('./response.php');
$db = new Database;
$response = new Response;
 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['logout'])){
                if($_POST['logout'] == 'logout_user'){
                    session_destroy();
                    header('location:http://localhost/college_ecom/public/index.php');
                }
            }
      
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
   

  }

  //products for sports section
  $sportsProducts = $db->getSportsProduct();
  //products for electronics section
  $electronicsProducts = $db->getElectronicsProduct();
  //featured products
  $featuredProducts = $db->featuredProducts();
 


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
    <title>Daily Shop | Home</title>

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
    <!-- <link id="switcher" href="css/theme-color/bridge-theme.css" rel="stylesheet"> -->
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
                                            <img src="img/flag/ind.jpg" alt="english flag">INDIA
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
                                        <!-- <a class="btn dropdown-toggle" href="#" type="button" id="dropdownMenu1" data-toggle="dropdown"
                      aria-haspopup="true" aria-expanded="true">
                      <i class="fa fa-usd"></i>USD
                      <span class="caret"></span>
                    </a> -->
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
    </section>
    <!-- / menu -->
    <!-- Start slider -->
    <section id="aa-slider">
        <div class="aa-slider-area">
            <div id="sequence" class="seq">
                <div class="seq-screen">
                    <ul class="seq-canvas">
                        <!-- single slide item -->
                        <li>
                            <div class="seq-model">
                                <img data-seq src="img/slider/background1.jpg" alt="Men slide img" />
                            </div>
                            <div class="seq-title">
                                <span data-seq>Save Up to 75% Off</span>
                                <!-- <h2 data-seq>Men Collection</h2> -->
                                <p data-seq>Premium Laptops.Hurry!</p>
                                <a data-seq href="http://localhost/college_ecom/public/product.php?id=2"
                                    class="aa-shop-now-btn aa-secondary-btn">SHOP NOW</a>
                            </div>
                        </li>
                        <!-- single slide item -->
                        <li>
                            <div class="seq-model">
                                <img data-seq src="img/slider/background2.jpeg" alt="Wristwatch slide img" />
                            </div>
                            <div class="seq-title">
                                <span data-seq>Save Up to 40% Off</span>
                                <!-- <h2 data-seq>Wristwatch Collection</h2> -->
                                <p data-seq>Premium Mobiles.Hurry!</p>
                                <a data-seq href="http://localhost/college_ecom/public/product.php?id=2"
                                    class="aa-shop-now-btn aa-secondary-btn">SHOP NOW</a>
                            </div>
                        </li>
                        <!-- single slide item -->
                        <li>
                            <div class="seq-model">
                                <img data-seq src="img/slider/background3.jpeg" alt="Women Jeans slide img" />
                            </div>
                            <div class="seq-title">
                                <span data-seq>Save Up to 75% Off</span>
                                <!-- <h2 data-seq>Jeans Collection</h2> -->
                                <p data-seq>Premium Headphones.Hurry!</p>
                                <a data-seq href="http://localhost/college_ecom/public/product.php?id=2"
                                    class="aa-shop-now-btn aa-secondary-btn">SHOP NOW</a>
                            </div>
                        </li>
                        <!-- single slide item -->
                        <li>
                            <div class="seq-model">
                                <img data-seq src="img/slider/background4.jpg" alt="Shoes slide img" />
                            </div>
                            <div class="seq-title">
                                <span data-seq>Save Up to 75% Off</span>
                                <!-- <h2 data-seq>Exclusive Shoes</h2> -->
                                <p data-seq>Premium Sports Wear.Hurry!</p>
                                <a data-seq href="http://localhost/college_ecom/public/product.php?id=1"
                                    class="aa-shop-now-btn aa-secondary-btn">SHOP NOW</a>
                            </div>
                        </li>
                        <!-- single slide item -->
                        <!-- <li>
                            <div class="seq-model">
                                <img data-seq src="img/slider/5.jpg" alt="Male Female slide img" />
                            </div>
                            <div class="seq-title">
                                <span data-seq>Save Up to 50% Off</span>
                                <h2 data-seq>Best Collection</h2>
                                <p data-seq>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus, illum.</p>
                                <a data-seq href="#" class="aa-shop-now-btn aa-secondary-btn">SHOP NOW</a>
                            </div>
                        </li> -->
                    </ul>
                </div>
                <!-- slider navigation btn -->
                <fieldset class="seq-nav" aria-controls="sequence" aria-label="Slider buttons">
                    <a type="button" class="seq-prev" aria-label="Previous"><span class="fa fa-angle-left"></span></a>
                    <a type="button" class="seq-next" aria-label="Next"><span class="fa fa-angle-right"></span></a>
                </fieldset>
            </div>
        </div>
    </section>
    <!-- / slider -->

    <!-- Products section -->
    <section id="aa-product">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="aa-product-area">
                            <div class="aa-product-inner">
                                <!-- start prduct navigation -->
                                <ul class="nav nav-tabs aa-products-tab">
                                    <li class="active"><a href="#men" data-toggle="tab">Sports</a></li>
                                    <!-- <li><a href="#women" data-toggle="tab">Women</a></li>  -->
                                    <!-- <li ><a href="#sports" data-toggle="tab">Sports</a></li> -->
                                    <li><a href="#electronics" data-toggle="tab">Electronics</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!-- Start sports product category -->
                                    <div class="tab-pane fade in active" id="men">
                                        <ul class="aa-product-catg">
                                            <!-- start single product item -->
                                            <?php if(!empty($sportsProducts)): ?>
                                            <?php foreach($sportsProducts as $sportProduct): ?>
                                            <li>
                                                <figure>
                                                    <a class="aa-product-img" href="#"><img
                                                            src="../public/photos/<?php echo $sportProduct->image; ?>"
                                                            alt="polo shirt img" class='product_image'></a>

                                                    <a class="aa-add-card-btn"
                                                        href="cart.php?id=<?php echo base64_encode($sportProduct->productID); ?>">
                                                        <span class="fa fa-shopping-cart"></span>Add To Cart</a>
                                                    <figcaption class="product_detail_sports">
                                                        <h4 class="aa-product-title"><a
                                                                href="#"><?php echo $sportProduct->Pname; ?></a>
                                                        </h4>
                                                        <h6 class="text-danger"><i class="far fa-star"></i>
                                                            <?php echo $sportProduct->rating; ?>
                                                        </h6>
                                                        <span
                                                            class="aa-product-price"><?php echo '&#x20b9;' . $sportProduct->price; ?></span>
                                                    </figcaption>
                                                </figure>
                                                <div class="aa-product-hvr-content">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Compare"><span class="fa fa-exchange"></span></a>
                                                    <a href="#" data-toggle2="tooltip" data-placement="top"
                                                        title="Quick View" data-toggle="modal"
                                                        data-target="#quick-view-modal"><span
                                                            class="fa fa-search"></span></a>
                                                </div>
                                                <!-- product badge -->
                                                <span class="aa-badge aa-sale" href="#">Sale!</span>
                                            </li>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                        <a class="aa-browse-btn"
                                            href="http://localhost/college_ecom/public/product.php?id=1">Browse all
                                            Product <span class="fa fa-long-arrow-right"></span></a>
                                    </div>
                                    <!-- / sports category -->


                                    <!-- start electronic product category -->
                                    <div class="tab-pane fade" id="electronics">
                                        <ul class="aa-product-catg">

                                            <!-- start single product item  -->
                                            <?php if(!empty($electronicsProducts)): ?>
                                            <?php foreach($electronicsProducts as $electronicProduct): ?>
                                            <li>
                                                <figure>
                                                    <a class="aa-product-img" href="#"><img
                                                            src="../public/photos/<?php echo $electronicProduct->image; ?>"
                                                            alt="polo shirt img" class='product_image'></a>
                                                    <a class="aa-add-card-btn"
                                                        href="cart.php?id=<?php echo base64_encode($electronicProduct->productID); ?>">
                                                        <span class="fa fa-shopping-cart"></span>Add To Cart</a>
                                                    <figcaption class="product_detail">
                                                        <h4 class="aa-product-title"><a href="#">This is Title</a></h4>
                                                        <h6 class="text-danger"><i class="far fa-star"></i>
                                                            <?php echo $sportProduct->rating; ?>
                                                        </h6>
                                                        <span
                                                            class="aa-product-price"><?php echo '&#x20b9;' . $electronicProduct->price; ?></span>
                                                    </figcaption>
                                                </figure>
                                                <div class="aa-product-hvr-content">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Compare"><span class="fa fa-exchange"></span></a>
                                                    <a href="#" data-toggle2="tooltip" data-placement="top"
                                                        title="Quick View" data-toggle="modal"
                                                        data-target="#quick-view-modal"><span
                                                            class="fa fa-search"></span></a>
                                                </div>
                                                <!-- product badge -->
                                                <span class="aa-badge aa-sale" href="#">SALE!</span>
                                            </li>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                        </ul>
                                        <a class="aa-browse-btn"
                                            href="http://localhost/college_ecom/public/product.php?id=2">Browse all
                                            Product <span class="fa fa-long-arrow-right"></span></a>
                                    </div>
                                    <!-- / electronic product category -->

                                </div>
                                <!-- quick view modal -->
                                <div class="modal fade" id="quick-view-modal" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                                <div class="row">
                                                    <!-- Modal view slider -->
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="aa-product-view-slider">
                                                            <div class="simpleLens-gallery-container" id="demo-1">
                                                                <div class="simpleLens-container">
                                                                    <div class="simpleLens-big-image-container">
                                                                        <a class="simpleLens-lens-image"
                                                                            data-lens-image="img/view-slider/large/polo-shirt-1.png">
                                                                            <img src="img/view-slider/medium/polo-shirt-1.png"
                                                                                class="simpleLens-big-image">
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="simpleLens-thumbnails-container">
                                                                    <a href="#" class="simpleLens-thumbnail-wrapper"
                                                                        data-lens-image="img/view-slider/large/polo-shirt-1.png"
                                                                        data-big-image="img/view-slider/medium/polo-shirt-1.png">
                                                                        <img
                                                                            src="img/view-slider/thumbnail/polo-shirt-1.png">
                                                                    </a>
                                                                    <a href="#" class="simpleLens-thumbnail-wrapper"
                                                                        data-lens-image="img/view-slider/large/polo-shirt-3.png"
                                                                        data-big-image="img/view-slider/medium/polo-shirt-3.png">
                                                                        <img
                                                                            src="img/view-slider/thumbnail/polo-shirt-3.png">
                                                                    </a>

                                                                    <a href="#" class="simpleLens-thumbnail-wrapper"
                                                                        data-lens-image="img/view-slider/large/polo-shirt-4.png"
                                                                        data-big-image="img/view-slider/medium/polo-shirt-4.png">
                                                                        <img
                                                                            src="img/view-slider/thumbnail/polo-shirt-4.png">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal view content -->
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <div class="aa-product-view-content">
                                                            <h3>T-Shirt</h3>
                                                            <div class="aa-price-block">
                                                                <span class="aa-product-view-price">$34.99</span>
                                                                <p class="aa-product-avilability">Avilability: <span>In
                                                                        stock</span></p>
                                                            </div>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                                                Officiis animi, veritatis
                                                                quae repudiandae quod nulla porro quidem, itaque quis
                                                                quaerat!</p>
                                                            <h4>Size</h4>
                                                            <div class="aa-prod-view-size">
                                                                <a href="#">S</a>
                                                                <a href="#">M</a>
                                                                <a href="#">L</a>
                                                                <a href="#">XL</a>
                                                            </div>
                                                            <div class="aa-prod-quantity">
                                                                <form action="">
                                                                    <select name="" id="">
                                                                        <option value="0" selected="1">1</option>
                                                                        <option value="1">2</option>
                                                                        <option value="2">3</option>
                                                                        <option value="3">4</option>
                                                                        <option value="4">5</option>
                                                                        <option value="5">6</option>
                                                                    </select>
                                                                </form>
                                                                <p class="aa-prod-category">
                                                                    Category: <a href="#">Sports Watch</a>
                                                                </p>
                                                            </div>
                                                            <div class="aa-prod-view-bottom">
                                                                <a href="#" class="aa-add-to-cart-btn"><span
                                                                        class="fa fa-shopping-cart"></span>Add To
                                                                    Cart</a>
                                                                <a href="#" class="aa-add-to-cart-btn">View Details</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- / quick view modal -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Products section -->
    <!-- banner section -->

    <!-- popular section -->
    <section id="aa-popular-category">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="aa-popular-category-area">
                            <!-- start prduct navigation -->
                            <ul class="nav nav-tabs aa-products-tab">
                                <li><a href="#featured" data-toggle="tab">Featured</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">


                                <!-- start featured product category -->
                                <div class="tab-pane fade in active" id="featured">
                                    <ul class="aa-product-catg aa-featured-slider">
                                        <!-- start single product item -->
                                        <?php if(!empty($featuredProducts)): ?>
                                        <?php foreach($featuredProducts as $featuredProduct): ?>
                                        <li>
                                            <figure>
                                                <a class="aa-product-img" href="#"><img
                                                        src="../public/photos/<?php echo $featuredProduct->image; ?>"
                                                        alt="polo shirt img" class='product_image'></a>
                                                <a class="aa-add-card-btn"
                                                    href="cart.php?id=<?php echo base64_encode($featuredProduct->productID); ?>">
                                                    <span class="fa fa-shopping-cart"></span>Add To Cart</a>
                                                <figcaption class="product_detail">
                                                    <h4 class="aa-product-title"><a
                                                            href="#"><?php echo  $featuredProduct->Pname; ?></a>
                                                    </h4>
                                                    <h6 class="text-danger"><i class="far fa-star"></i>
                                                        <?php echo $sportProduct->rating; ?>
                                                    </h6>
                                                    <span
                                                        class="aa-product-price"><?php echo '&#x20b9;' . $featuredProduct->price; ?></span>
                                                </figcaption>
                                            </figure>
                                            <div class="aa-product-hvr-content">
                                                <a href="#" data-toggle="tooltip" data-placement="top"
                                                    title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>
                                                <a href="#" data-toggle="tooltip" data-placement="top"
                                                    title="Compare"><span class="fa fa-exchange"></span></a>
                                                <a href="#" data-toggle2="tooltip" data-placement="top"
                                                    title="Quick View" data-toggle="modal"
                                                    data-target="#quick-view-modal"><span
                                                        class="fa fa-search"></span></a>
                                            </div>
                                            <!-- product badge -->
                                            <span class="aa-badge aa-sale" href="#">FEATURED!</span>
                                        </li>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        <!-- start single product item  -->


                                    </ul>
                                    <a class="aa-browse-btn"
                                        href="http://localhost/college_ecom/public/product.php?id=3">Browse all Product
                                        <span class="fa fa-long-arrow-right"></span></a>
                                </div>
                                <!-- / featured product category -->



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / popular section -->
    <!-- Support section -->
    <section id="aa-support">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-support-area">
                        <!-- single support -->
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="aa-support-single">
                                <span class="fa fa-truck"></span>
                                <h4>FREE SHIPPING</h4>
                                <P>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam, nobis.</P>
                            </div>
                        </div>
                        <!-- single support -->
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="aa-support-single">
                                <span class="fa fa-clock-o"></span>
                                <h4>30 DAYS MONEY BACK</h4>
                                <P>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam, nobis.</P>
                            </div>
                        </div>
                        <!-- single support -->
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="aa-support-single">
                                <span class="fa fa-phone"></span>
                                <h4>SUPPORT 24/7</h4>
                                <P>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam, nobis.</P>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Support section -->
    <!-- Testimonial -->
    <section id="aa-testimonial">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-testimonial-area">
                        <ul class="aa-testimonial-slider">
                            <!-- single slide -->
                            <li>
                                <div class="aa-testimonial-single">
                                    <img class="aa-testimonial-img" src="img/women/test1.jpg" alt="testimonial img">
                                    <span class="fa fa-quote-left aa-testimonial-quote"></span>
                                    <p>Brilliant Website.Had a Teriffic Shopping Experience! </p>
                                    <div class="aa-testimonial-info">
                                        <p>Shwetlana Gupta</p>
                                        <span>Designer</span>
                                        <a href="#">Dribble.com</a>
                                    </div>
                                </div>
                            </li>
                            <!-- single slide -->
                            <li>
                                <div class="aa-testimonial-single">
                                    <img class="aa-testimonial-img" src="img/women/test3.jpg" alt="testimonial img">
                                    <span class="fa fa-quote-left aa-testimonial-quote"></span>
                                    <p>Awesowe Deals.Valueable Products for Reasonable price!</p>
                                    <div class="aa-testimonial-info">
                                        <p>KEVIN MEYER</p>
                                        <span>Broker</span>
                                        <a href="#">Shah Properties!</a>
                                    </div>
                                </div>
                            </li>
                            <!-- single slide -->
                            <li>
                                <div class="aa-testimonial-single">
                                    <img class="aa-testimonial-img" src="img/women/test2.jpg" alt="testimonial img">
                                    <span class="fa fa-quote-left aa-testimonial-quote"></span>
                                    <p>Love The wide range of gadgets and support service after Product Delivery!.</p>
                                    <div class="aa-testimonial-info">
                                        <p>Luner</p>
                                        <span>COO</span>
                                        <a href="#">Kinatic Solution</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- / Testimonial -->





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
                                                </p>
                                                <p><span class="fa fa-envelope"></span>dailyshop@gmail.com</p>
                                            </address>
                                            <!-- <div class="aa-footer-social">
                                                <a href="#"><i class="fa fa-facebook-official" aria-hidden="true"></i>
                                                </a>
                                                <a href="#"><span class="fa fa-twitter"></span></a>
                                                <a href="#"><span class="fa fa-google-plus"></span></a>
                                                <a href="#"><span class="fa fa-youtube"></span></a>
                                            </div> -->
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
                            <p><a href="">DailyShop</a></p>
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