<?php
    Class Product{
        protected $idproduct;
        protected $nameproduct;
        protected $idcatalog;
        protected $idbrand;
        protected $price;
        protected $quantity;
        protected $discount;
        protected $description;
        protected $state;
        protected $image;
        public function __construct(?string $idproduct = null,?string $nameproduct = null,?string $image=null, ?string $idcatalog = null,?string $idbrand = null,?float $price = 0.0,?int $quantity = 0,?float $discount = 0.0,?string $state=null,?string $description=null) {
            $this->idproduct=$idproduct;
            $this->nameproduct = $nameproduct;
            $this->image=$image;
            $this->idcatalog = $idcatalog;
            $this->idbrand=$idbrand;
            $this->price=$price;
            $this->quantity=$quantity;
            $this->discount=$discount;
            $this->state=$state;
            $this->description=$description;
        }
        public function setIdProduct($idproduct)
        {
            $this->idproduct=$idproduct;
        }
        public function setNameProduct($nameproduct)
        {
            $this->nameproduct=$nameproduct;
        }
        public function setIdBrand($idbrand)
        {
            $this->idbrand=$idbrand;
        }
        public function setPrice($price)
        {
            $this->price=$price;
        }
        public function setQuantity($quantity)
        {
            $this->quantity=$quantity;
        }
        public function setDiscount($discount)
        {
            $this->discount=$discount;
        }
        public function setIdCatalog($idcatalog)
        {
            $this->idcatalog=$idcatalog;
        }
        public function setDescription($description)
        {
            $this->description=$description;
        }
        public function setState($state)
        {
            $this->state=$state;
        }

        public function getIdProduct()
        {
            return $this->idproduct;
        }
        public function getNameProduct()
        {
            return $this->nameproduct;
        }
        public function getIdCatalog()
        {
            return $this->idcatalog;
        }
        public function getIdBrand()
        {
            return $this->idbrand;
        }
        public function getPrice()
        {
            return $this->price;
        }
        public function getQuantity()
        {
            return $this->quantity;
        }
        public function getDiscount()
        {
            return $this->discount;
        }
        public function getDescription()
        {
            return $this->description;
        }
        public function getState()
        {
            return $this->state;
        }
        public function setImage($image)
        {
            $this->image=$image;
        }
        public function getImage()
        {
            return $this->image;
        }
        public static function validateName($ProductName)
        {
            try{
                $sql="call sp_validateNameProduct('$ProductName')";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                return true;
            }
            catch(PDOException $ex)
            {
                return false;
            }
        }
        public static function getNameProductbyId($id)
        {
            try
            {
                $sql="select nameproduct from product where idproduct='$id'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                    return $data[0]['nameproduct'];
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getNewId()
        {
            try{
                $sql="call sp_getNewIDProduct;";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC)[0]["ID"];
                return $data;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function insert_Product()
        {
            try
            {
                $sql="INSERT INTO product VALUES ('', '$this->nameproduct','$this->idcatalog','$this->idbrand','$this->price','$this->quantity','$this->discount','$this->description','$this->state');";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return null;
            }
            catch(PDOException $ex){
                return $ex->getMessage() ;
            }
        }
        public function getPriceAfterDiscount()
        {
            return $this->price-($this->price*$this->discount);
        }
        public static function getSlideImageById($id)
        {
            try
            {
                $sql="call sp_selectSlideImageById('$id')";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                $list=array();
                foreach($data as $slideimage)
                {
                    $list[]=new Image($slideimage['idimage'],$slideimage['fileimage']);
                }
                return $list;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getTotalProduct()
        {
            try
            {
                $sql="select count(*) as `total`, SUM(IF(product.State='NEW', 1, 0)) AS `new`,  SUM(IF(product.State='HIDDEN', 1, 0)) AS `hidden`,SUM(IF(product.State='OOS', 1, 0)) AS `out of stock`  from product";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                    return $data[0];
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getProductById($ID)
        {
            try{
                $sql="call sp_selectProductById('".$ID."')";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    return new Product($data['idproduct'],$data['nameproduct'],Image::getDefaultImage($data['fileimage']),$data['namecatalog'],$data['namebrand'],$data['price'],$data['quantity'],$data['discount'],$data['state'],$data['description']);
                }
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getTotalProductWithCondition($name,$catalog,$brand,$typeacc)
        {
            $count=0;
            $findString="select count(*) as `total`, SUM(IF(product.State='NEW', 1, 0)) AS `new`,  SUM(IF(product.State='HIDDEN', 1, 0)) AS `hidden`,SUM(IF(product.State='OOS', 1, 0)) AS `out of stock` from product left join catalog on product.IdCatalog=catalog.IdCatalog Left join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage";
            try{
                if(!empty($name))
                {
                    $findString.=" where (nameproduct like '%".$name."%' or product.idproduct like '%".$name."%')";
                    $count++;
                }
                if(!empty($catalog))
                {
                    if($count>0)
                    {
                        $findString.=" and ";
                    }
                    else
                    {
                        $findString.=" where ";
                        $count++;
                    }
                    $findString.="product.idcatalog like '%".$catalog."%'";
                }
                if(!empty($brand))
                {
                    if($count>0)
                        $findString.=" and ";
                    else
                    {
                        $findString.=" where ";
                        $count++;
                    }
                    $findString.="product.idbrand like '%".$brand."%'";
                }
                if($typeacc!="ADMIN")
                {
                    if($count>0)
                        $findString.=" and ";
                    else
                        $findString.=" where ";
                    $findString.=" product.state!='HIDDEN'";
                }
                $stmt=Database::$pdo->prepare($findString);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("Sorry! We can not find any product!")</script>';
                else
                    return $data[0];
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function getProductWithCondition($name,$catalog,$brand,$offset,$limit,$typeacc)
        {
            $count=0;
            $findString="Select product.idproduct,product.nameproduct,fileimage,catalog.namecatalog,brand.namebrand,price,discount,quantity,state,description from product left join catalog on product.IdCatalog=catalog.IdCatalog Left join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage";
            try{
                if(!empty($name))
                {
                    $findString.=" where (nameproduct like '%".$name."%' or product.idproduct like '%".$name."%')";
                    $count++;
                }
                if(!empty($catalog))
                {
                    if($count>0)
                        $findString.=" and ";
                    else
                    {
                        $findString.=" where ";
                        $count++;
                    }
                    $findString.="product.idcatalog like '%".$catalog."%'";
                }
                if(!empty($brand))
                {
                    if($count>0)
                        $findString.=" and ";
                    else
                        $findString.=" where ";
                    $findString.="product.idbrand like '%".$brand."%'";
                }
                if($typeacc!="ADMIN")
                    $findString.=" and product.state!='HIDDEN' ";
                $findString.="ORDER by product.idproduct LIMIT :limit OFFSET :offset";
                $stmt=Database::$pdo->prepare($findString);
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset,PDO::PARAM_INT);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("Sorry! We can not find any product!")</script>';
                else
                {
                    $productlist = array();
                    foreach($data as $info)
                    {
                        $product = new Product($info['idproduct'],$info['nameproduct'],Image::getDefaultImage($info['fileimage']),$info['namecatalog'],$info['namebrand'],$info['price'],$info['quantity'],$info['discount'],$info['state'],$info['description']);
                        $productlist[]=$product;
                    }
                    return $productlist;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function getAllProducts()
        {
            try{
                $sql="call sp_selectAllProductInfo";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    
                    $productlist = array();
                    foreach($data as $info)
                    {
                        $product = new Product($info['idproduct'],$info['nameproduct'],Image::getDefaultImage($info['fileimage']),$info['namecatalog'],$info['namebrand'],$info['price'],$info['quantity'],$info['discount'],$info['state'],$info['description']);
                        $productlist[]=$product;
                    }
                    return $productlist;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function getProductsPerPage($offset,$limit,$typacc)
        {
            try{
                $sql="Select product.idproduct,nameproduct,fileimage,catalog.namecatalog,brand.namebrand,price,discount,quantity,state,description from product left join catalog on product.IdCatalog=catalog.IdCatalog Left join brand on product.IdBrand=brand.IdBrand LEFT JOIN imagedetails ON product.IdProduct=imagedetails.IdProduct and(ImageType LIKE '%POST%' OR ImageType =NULL) LEFT JOIN image on imagedetails.IdImage=image.IdImage ";
                if($typacc!="ADMIN")
                    $sql.=" where product.state!='HIDDEN' ";
                $sql.="ORDER by product.idproduct LIMIT :limit OFFSET :offset";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset,PDO::PARAM_INT);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    $productlist = array();
                    foreach($data as $info)
                    {
                        $product = new Product($info['idproduct'],$info['nameproduct'],Image::getDefaultImage($info['fileimage']),$info['namecatalog'],$info['namebrand'],$info['price'],$info['quantity'],$info['discount'],$info['state'],$info['description']);
                        $productlist[]=$product;
                    }
                    return $productlist;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function updateQuantity($id,$quantity)
        {
            try{
                $sql="UPDATE `product` SET `Quantity` = `Quantity`- '$quantity' WHERE `product`.`IdProduct` = '$id';";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public static function checkExistedandAvailable($idproduct,$quantity)
        {
            try{
                $sql="SELECT idproduct FROM product WHERE idproduct='".$idproduct."' and quantity >='".$quantity."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $product=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($product))
                    return 1;
                return 0;
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function updateProduct()
        {
            $count=0;
            $sql="UPDATE `product` SET";
            if(!empty($this->nameproduct))
            {
                $count++;
                $sql.=" `NameProduct`='$this->nameproduct'";
            }
            
            if(!empty($this->state))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `State`='$this->state'";
                
            }
            
            if(!empty($this->price))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `Price`='$this->price'";
                
            }
           
            if(!empty($this->discount))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `Discount`='$this->discount'";
               
            }
           
            if(!empty($this->idcatalog))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `IdCatalog`='$this->idcatalog'";
                
            }
            
            if(!empty($this->idbrand))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `IdBrand`='$this->idbrand'";
                
            }
           
            if(!empty($this->quantity))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `Quantity`='$this->quantity'";
                
            }
           
            if(!empty($this->description))
            {
                if($count>0)
                    $sql.=",";
                $count++;
                $sql.=" `Description`='$this->description'";
            }
            $sql.=" where `IdProduct`='$this->idproduct'";
            if($count>0)
            {
                try{
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->execute();
                    return 1;
                }
                catch(PDOException $ex)
                {
                    return 0;
                    echo '<script>alert("'.$ex->getMessage().'")</script>';
                }
            }
            return 0;
            
        }
        public static function checkProductInOrderDetails($id)
        {
            //0: Invalid IdProduct, 1: Product can be deleted, 2: Product has been purchase!//
            try{
                $sql="SELECT orderdetails.IdProduct,product.NameProduct from product left join orderdetails on `product`.IdProduct=`orderdetails`.IdProduct left join orders on orders.IdOrder=orderdetails.IdOrder and orders.Delivery= 'CONFIRMED' where product.IdProduct='$id' LIMIT 1;";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $product=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($product))
                    return 0;
                else
                {
                    if(empty($product[0]['IdProduct'])&&!empty($product[0]['NameProduct']))
                        return 1;
                    else  if(!empty($product[0]['IdProduct'])&&!empty($product[0]['NameProduct']))
                        return 2;
                    return 0;
                }
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public static function deleteProduct($idproduct)
        {
            try{
                $sql="Delete from `product` where `idproduct`=:id";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':id',$idproduct,PDO::PARAM_STR);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex)
            {
                return 0;
            }
        }
    }