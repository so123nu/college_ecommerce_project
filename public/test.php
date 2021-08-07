<?php
require_once('../private/Database.php');
require_once('./response.php');
$db = new Database;
$response = new response();

$response = new Response;
    
    $lowerLimit = $_POST['lower_limit'];
    $upperLimit = $_POST['upper_limit'];

    $products = $db->getProductsByPrice($lowerLimit,$upperLimit);
    $html = "";

     if(!empty($products)){
 foreach($products as $product){
  $html .= '<li>
    <figure>
        <a class="aa-product-img" href="#"><img src="../public/photos/'.  $product->image .'"
alt="polo shirt img" class="product_image"></a>

<a class="aa-add-card-btn" href="cart.php?id='.base64_encode($product->productID).'">
<span class="fa fa-shopping-cart"></span>Add To Cart</a>
<figcaption class="product_detail_sports">
    <h4 class="aa-product-title"><a href="#">'.$product->Pname .'</a>
</h4>
<span class="aa-product-price"> "&#x20b9;"  '.$product->price .'</span>
</figcaption>
</figure>
<div class="aa-product-hvr-content">
    <a href="#" data-toggle="tooltip" data-placement="top" title="Add to Wishlist"><span
            class="fa fa-heart-o"></span></a>
    <a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><span class="fa fa-exchange"></span></a>
    <a href="#" data-toggle2="tooltip" data-placement="top" title="Quick View" data-toggle="modal"
        data-target="#quick-view-modal"><span class="fa fa-search"></span></a>
</div>
<!-- product badge -->
<span class="aa-badge aa-sale" href="#">Sale!</span>
</li>';

}
}

$response->success("success",json_encode($html));

?>