 <?php 

class Database{
    //Set database parameters
    private $dbname = "college_ecom";
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
    $password = password_hash($password,PASSWORD_BCRYPT);
     $sql = 'INSERT INTO `users`(`name`, `email`, `aadhaar`, `password`, `is_active`) VALUES (:name,:email,:aadhaar,:password,:is_active)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':aadhaar',$aadhaar);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':is_active',$is_active);

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
    $sql = 'Insert into sellers (name,email,password,gst,pan) values (:name,:email,:password,:gst,:pan)';
    $this->prepare($sql);
     // bind values to prepared variables
    $this->bind(':name',$name);
    $this->bind(':gst',$gst);
    $this->bind(':email',$email);
    $this->bind(':password',$password);
    $this->bind(':pan',$pan);

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
    $sql = 'SELECT * FROM  category_table';
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
  $sql = 'SELECT * FROM `product` join category_table on product.category_ID = category_table.category_ID
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
  $sql = 'SELECT * FROM `product` join category_table on product.category_ID = category_table.category_ID
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
   $sql = 'SELECT * FROM `product` Where productID = :productID';
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
  $sql = 'Insert into cart (product_id,price,product_name,product_image,quantity,user_id) values (:product_id,:price,:product_name,:product_image,:quantity,:user_id)';
  $this->prepare($sql);
   // bind values to prepared variables
  $this->bind(':product_id',$productDetails->productID);
  $this->bind(':price',$productDetails->price);
  $this->bind(':product_name',$productDetails->Pname);
  $this->bind(':product_image',$productDetails->image);
  $this->bind(':quantity',1);
  $this->bind(':user_id',$userId);

 //execute query
  if($this->execute()){
      return true;
  }else{
      return false;
  }
}

public function getCartByUser($userId){
  //sql query to select user sports products
  $sql = 'SELECT * FROM `cart` Where user_id = :userID';
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
  $sql = 'SELECT * FROM `cart` Where user_id = :userID';
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
  $sql = 'DELETE FROM `cart` WHERE product_id=:product_id';
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
   

}

?>