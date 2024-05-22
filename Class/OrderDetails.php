<?php
    class OrderDetails
    {
        private $idorder;
        private $idproduct;
        private $quantity;
        private $savedprice;
        private $saveddiscount;
        public function __construct(?string $idorder = null,?string $idproduct = null,?int $quanity=0,?float $savedprice=0.0,?float $saveddiscount=0.0)
        {
            $this->idorder=$idorder;
            $this->idproduct=$idproduct;
            $this->quantity=$quanity;
            $this->saveddiscount=$saveddiscount;
            $this->savedprice=$savedprice;
        }
        public function setSavedPrice($savedprice)
        {
            $this->savedprice=$savedprice;
        }
        public function setSavedDiscount($saveddiscount)
        {
            $this->saveddiscount=$saveddiscount;
        }
        public function setIdOrder($idorder)
        {
            $this->idorder=$idorder;
        }
        public function setIdProduct($idproduct)
        {
            $this->idproduct=$idproduct;
        }
        public function setQuantity($quanity)
        {
            $this->quantity=$quanity;
        }
        public function getIdOrder()
        {
            return $this->idorder;
        }
        public function getIdProduct()
        {
            return $this->idproduct;
        }
        public function getQuantity()
        {
            return $this->quantity;
        }
        public function getSavedPrice()
        {
            return $this->savedprice;
        }
        public function getSavedDiscount()
        {
            return $this->saveddiscount;
        }
        public function selectDetailByOrderId($id)
        {
            try{
                $sql="SELECT * FROM orderdetails WHERE idorder='".$id."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $details=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($details))
                {
                    $list=array();
                    foreach($details as $item)
                    {
                        $detail=new OrderDetails($item['IdOrder'],$item['IdProduct'],$item['Quantity'],$item['SavedPrice'],$item['SavedDiscount']);
                        $list[]=$detail;
                    }
                    return $list;
                }
                return null;
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function insertOrderDetail()
        {
            try{
                $sql="Insert into `orderdetails` values(:idorder,:idproduct,:quantity,:price,:discount)";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':idorder',$this->idorder,PDO::PARAM_STR);
                $stmt->bindParam(':idproduct',$this->idproduct,PDO::PARAM_STR);
                $stmt->bindParam(':quantity',$this->quantity,PDO::PARAM_STR);
                $stmt->bindParam(':price',$this->savedprice,PDO::PARAM_STR);
                $stmt->bindParam(':discount',$this->saveddiscount,PDO::PARAM_STR);
                $stmt->execute();
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function updateDetails($idorder,$idproduct,$quantity,$price,$discount)
        {
            try{
                $sql="UPDATE `orderdetails` SET `Quantity` = '$quantity', `SavedPrice` = '$price',`SavedDiscount`='$discount' WHERE `orderdetails`.`IdOrder` = '$idorder' and `orderdetails`.`IdProduct` = '$idproduct';";
                $stmt=Database::$pdo->prepare($sql);
                echo '<script>alert("'.$sql.'")</script>';
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function updateItem()
        {
            try
            {
                if($this->quantity>0)
                {
                    $sql="UPDATE orderdetails set quantity=:quantity where idproduct=:idproduct and idorder=:idorder";
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->bindParam(':quantity',$this->quantity,PDO::PARAM_STR);
                    $stmt->bindParam(':idproduct',$this->idproduct,PDO::PARAM_STR);
                    $stmt->bindParam(':idorder',$this->idorder,PDO::PARAM_STR);
                    $stmt->execute();
                    return 1;
                }
                else
                {
                    $this->deleteItem();
                    return 1;
                }
            }
            catch(PDOException $ex){
                 echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function deleteItem()
        {
            try
            {
                $sql="Delete from orderdetails where idproduct=:idproduct and idorder=:idorder";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':idproduct',$this->idproduct,PDO::PARAM_STR);
                $stmt->bindParam(':idorder',$this->idorder,PDO::PARAM_STR);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex){
                return 0;
            }
        }
        public function insertItem()
        {
            try
            {
                try{
                $sql ="Select * from `orderdetails` where `orderdetails`.IdOrder='$this->idorder' and `orderdetails`.IdProduct='$this->idproduct'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                 catch(PDOException $ex){
                    echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
                    return 0;
                     
                 }
                if(empty($data))
                {
                    $sql="Insert into `orderdetails` values('$this->idorder','$this->idproduct','$this->quantity',0,0)";
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->execute();
                    return 1;
                }
                else
                {
                    $sql="SELECT orderdetails.Quantity as current, product.Quantity as max from product,orderdetails where product.IdProduct=orderdetails.IdProduct and product.IdProduct=:idproduct and orderdetails.IdOrder=:idorder";
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->bindParam(':idproduct',$this->idproduct,PDO::PARAM_STR);
                    $stmt->bindParam(':idorder',$this->idorder,PDO::PARAM_STR);
                    $stmt->execute();
                    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    var_dump($data);
                    if(!empty($data))
                    {
                        $max=$data[0]['max'];
                        $current=$data[0]['current'];
                        if($this->quantity+$current>$max)
                        {
                            $this->quantity=$max;
                            $this->updateItem();
                            return 1;
                        }
                        else
                        {
                            $this->quantity+=$current;
                            $this->updateItem();
                            return 1;
                        }
                    }

                }
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
                return 0;
               
            }
        }
        public static function deleteListOrderdetails($list)
        {
            try
            {
                $sql="Delete from orderdetails where idorder IN ($list);";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
                return 0;
            }
        }
    }