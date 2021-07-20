 <?php 

class Database{
    //Set database parameters
    // private $dbname = "college_ecom";
    private $dbname = "college_ecom_adminpanel";
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    protected $dbh , $stmt , $errmsg;
    
    public function __construct(){
       //Set dsn value
       $dsn =  'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
       
       //Set error type and establish persistent connection 
        $options = array(
            PDO::ATTR_PERSISTENT => 'true',
            PDO::ATTR_ERRMODE => 'PDO::EXCEPTION'
        );

        try{
            // Instantiate PDO class
            $this->dbh = new PDO($dsn,$this->username,$this->password,$options);
        }catch(PDOException $e){
            //Display errors if connection not established
            $this->errmsg = $e->getMessage();
            echo $this->errmsg;
        }
   }

  //  helpers
   
   public function prepare($sql){
     //prepare query
     $this->stmt = $this->dbh->prepare($sql);
   }

   public function bind($param,$value){
     //bind value to query
     $this->stmt->bindValue($param,$value);
   }
    
   public function execute(){
     //execute Query
     $this->stmt->execute();
    
   }

   public function lastInsertedId(){
    return $this->dbh->lastInsertId();
   }

   public function fetchSingle(){
     //Fetch Single record from DB
      return $this->stmt->fetch(PDO::FETCH_OBJ);
   }
  
  public function fetchMultiple(){
    //Fetch Multiple Record From DB
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }
  
   public function rowCount(){
     //Fetch row count from DB
      return $this->stmt->rowCount();
  }

  public function getDate(){
    return date("Y-m-d h:i:s");
  }

  // end helpers

  public function validateUserEmail($email){
    //sql query to select user using email
    $sql = 'SELECT * FROM users WHERE email=:email';
    //prepare query
    $this->prepare($sql);
    //bind email value to :email
    $this->bind(':email',$email);
    //execute query
    $this->execute();
    //fetch rowCount
    $rowCount = $this->rowCount();
   //if rowCount > 0 i.e., user exists then return true else return false
    if($rowCount > 0){
        return true;
    }else{
        return false;
     }
  }

  public function validateSellerEmail($email){
  //sql query to select user using email
  $sql = 'SELECT * FROM sellers WHERE email=:email';
  //prepare query
  $this->prepare($sql);
  //bind email value to :email
  $this->bind(':email',$email);
  //execute query
  $this->execute();
  //fetch rowCount
  $rowCount = $this->rowCount();
  //if rowCount > 0 i.e., user exists then return true else return false
  if($rowCount > 0){
      return true;
  }else{
      return false;
    }
  }

  //register user
  public function registerUser($name,$aadhaar,$email,$password){
    $is_active = 1;
    $date = $this->getDate();
    $password = password_hash($password,PASSWORD_BCRYPT);
     $sql = 'INSERT INTO `users`(`name`, `email`, `aadhaar`, `password`, `is_active`,`created_at`) VALUES (:name,:email,:aadhaar,:password,:is_active,:created_at)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':aadhaar',$aadhaar);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':is_active',$is_active);
    $this->bind(':created_at',$date);

   //execute query
    if($this->execute()){
        return true;
    }else{
        return false;
    }
 }


  //register Seller
  public function registerSeller($name,$email,$password,$gst,$pan){
    $password = password_hash($password,PASSWORD_BCRYPT);
    $date = $this->getDate();
    $sql = 'Insert into sellers (name,email,password,gst,pan,created_at) values (:name,:email,:password,:gst,:pan,:created_at)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':gst',$gst);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':pan',$pan);
    $this->bind(':created_at',$date);

   //execute query
    if($this->execute()){
        return true;
    }else{
        return false;
    }
 }

 //Get User Details
 public function userDetails($email){
    //sql query to select user using email
    $sql = 'SELECT * FROM  users WHERE email=:email';
    //prepare query
    $this->prepare($sql);
      //bind email value to :email
    $this->bind(':email',$email);
    //execute query
    $this->execute();
    //fetch single user record
    $result = $this->fetchSingle();
    //return user details
    return $result;
}

//Get Seller Details
public function sellerDetails($email){
  //sql query to select user using email
  $sql = 'SELECT * FROM  sellers WHERE email=:email';
  //prepare query
  $this->prepare($sql);
    //bind email value to :email
  $this->bind(':email',$email);
  //execute query
  $this->execute();
  //fetch single user record
  $result = $this->fetchSingle();
  //return user details
  return $result;
}


public function fetchCategory(){
    //sql query to select user using email
    $sql = 'SELECT * FROM  categories';
    //prepare query
    $this->prepare($sql);
    //execute query
    $this->execute();
    //fetch single user record
    $result = $this->fetchMultiple();
    //return user details
    return $result;

}


public function getSportsProduct(){

  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` join categories on products.category_ID = categories.category_ID
  Where category = "Sports"';
  //prepare query
  $this->prepare($sql);
  //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;

}

public function getElectronicsProduct(){

  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` join categories on products.category_ID = categories.category_ID
  Where category = "Electronics"';
  //prepare query
  $this->prepare($sql);
  //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;

}

public function getProductDetails($productId){
   //sql query to select user sports products
   $sql = 'SELECT * FROM `products` Where productID = :productID';
   //prepare query
   $this->prepare($sql);
   //bind product id 
   $this->bind(':productID',$productId);
    //execute query
   $this->execute();
   //fetch multiple sports products record
   $result = $this->fetchSingle();
   //return sports products
   return $result;
}

public function addProductToCart($productDetails,$userId){
  $date = $this->getDate();
  $sql = 'Insert into carts (product_id,price,product_name,product_image,product_price,quantity,user_id,created_at) values (:product_id,:price,:product_name,:product_image,:product_price,:quantity,:user_id,:created_at)';
  $this->prepare($sql);
   // bind values to prepared variables
  $this->bind(':product_id',$productDetails->productID);
  $this->bind(':price',$productDetails->price);
  $this->bind(':product_price',$productDetails->price);
  $this->bind(':product_name',$productDetails->Pname);
  $this->bind(':product_image',$productDetails->image);
  $this->bind(':quantity',1);
  $this->bind(':user_id',$userId);
  $this->bind(':created_at',$date);

 //execute query
  if($this->execute()){
      return true;
  }else{
      return false;
  }
}

public function getCartByUser($userId){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `carts` Where user_id = :userID';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':userID',$userId);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  return $result;
}


   

public function getCartCount($userId){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `carts` Where user_id = :userID';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':userID',$userId);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return sports products
  $rowCount = $this->rowCount();

  return $rowCount;
}

public function deleteCartItem($id){
  $sql = 'DELETE FROM `carts` WHERE product_id=:product_id';
  //prepare query
  $this->prepare($sql);
  //bind product id 
  $this->bind(':product_id',$id);
   //execute query
  if($this->execute()){
    return true;
  }else{
    return false;
  }
  
}

//featured products
public function featuredProducts(){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `products` p JOIN ratings r on p.productID = r.pID WHERE r.rating > :rating';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':rating',3.5);
   //execute query
  $this->execute();
  //fetch multiple sports products record
  $result = $this->fetchMultiple();
  //return featured products
  return $result;

}

public function getCartSingleElement($productId){
  //sql query to select user sports products
  $sql = 'Select * from carts Where product_id = :productId';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':productId',$productId);
   //execute query
  $this->execute();
  
   $result = $this->fetchSingle();
   return $result;
}


public function updateCart($productId,$quantity){
//sql query to select user sports products
$sql = 'Select * from carts Where product_id = :productId';
//prepare query
$this->prepare($sql);
//bind rating 
$this->bind(':productId',$productId);
 //execute query
$this->execute();

$result = $this->fetchSingle();
  
//updated cart details
if($quantity > 0){

  $finalquantity = $quantity;
  $finalprice = $finalquantity * $result->product_price;

  $sql = 'Update carts SET price=:price, quantity =:quantity Where product_id =:productId';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':productId',$result->product_id);
  $this->bind(':price',$finalprice);
  $this->bind(':quantity',$finalquantity);
  //execute query
  $this->execute();
  return $finalquantity;
}

}

public function applyCoupon($couponCode,$cartTotal){
  $sql = 'Select * from coupons Where coupon_code =:coupon_code';
  //prepare query
  $this->prepare($sql);
  //bind rating 
  $this->bind(':coupon_code',$couponCode);
  $this->execute();
  $result = $this->fetchSingle();
  $rowCount = $this->rowCount();

  if($rowCount == 0){
    return '<div class="alert alert-danger"><strong>Failed</strong>.Invalid Coupon Code!</div>';
  }else{
    if($result->is_active = 0){
      return '<div class="alert alert-danger"><strong>Failed</strong>.Coupon Code has Expired!</div>';
    }else{
      
        $discountedPrice = $cartTotal *  ($result->discount_percentage/100);
         
        if($discountedPrice > $result->discount_upto){
          $total = $cartTotal - $result->discount_upto;
          return $total;
        }else{
          $total = $cartTotal - $result->discountedPrice;
          return $total;
        }

    }
     
  }


}

public function storePaymentDetails($userId,$paymentId,$amount){
    $date = $this->getDate();
    $sql = 'Insert into orders (user_id,total_amount,payment_id,order_status,created_at,payment_status) values (:user_id,:total_amount,:payment_id,:order_status,:created_at,:payment_status)';
    $this->prepare($sql);
    //bind rating 
    $this->bind(':user_id',$userId);
    $this->bind(':payment_id',$paymentId);
    $this->bind(':total_amount',$amount);
    $this->bind(':order_status','received');
    $this->bind(':created_at',$date);
    $this->bind(':payment_status','Paid');
    $this->execute();

    return $this->lastInsertedId();
  
}

public function storeOrderDetails($userId,$orderId){
    $carts = $this->getCartByUser($userId);  
    $date = $this->getDate(); 

    foreach($carts as $cart){
      $sql = 'Insert into order_details (order_id,user_id,product_id,product_price,product_name,product_image,quantity,created_at) 
              values (:order_id,:user_id,:product_id,:product_price,:product_name,:product_image,:quantity,:created_at)';
      $this->prepare($sql);
      //bind order details 
      $this->bind(':order_id',$orderId);
      $this->bind(':user_id',$userId);
      $this->bind(':product_id',$cart->product_id);
      $this->bind(':product_price',$cart->product_price);
      $this->bind(':product_name',$cart->product_name);
      $this->bind(':product_image',$cart->product_image);
      $this->bind(':quantity',$cart->quantity);
      $this->bind(':created_at',$date);
      $this->execute();
    }

    //Delete Cart Details
      $sql = 'DELETE FROM `carts` WHERE user_id=:user_id';
      $this->prepare($sql);
      //bind user ID
      $this->bind(':user_id',$userId);
      $this->execute();
    
 
}



}

?>