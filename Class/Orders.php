<?php
    Class Orders{
        private $idorder;
        private $idacc;
        private $date;
        private $total;
        private $delivery;
        private $payment;
        private $name;
        private $phonenumber;
        private $address;
        private $numberofpurchased;
        public function __construct( ?string $idorder = null,?string $idacc = null,?float $total=0,?string $name=null,?string $phonenumber=null,?string $address=null) {
            $this->idorder=$idorder;
            $this->idacc = $idacc;
            $this->date=date("Y-m-d H:i:s");
            $this->delivery='IN PROGRESS';
            $this->total=$total;
            $this->payment='NONE';
            $this->name=$name;
            $this->phonenumber=$phonenumber;
            $this->address=$address;

        }
        public function setName($name)
        {
            $this->name=$name;
        }
        public function setNumberofPurchased($numberofpurchased)
        {
            $this->numberofpurchased=$numberofpurchased;
        }
        public function setPhonenumber($phonenumber)
        {
            $this->phonenumber=$phonenumber;
        }
        public function setAddress($address)
        {
            $this->address=$address;
        }
        public function setIdOrder($idorder)
        {
            $this->idorder=$idorder;
        }
        public function setIdAcc($idacc)
        {
            $this->idacc=$idacc;
        }
        public function setDate($date)
        {
            $this->date=$date;
        }
        public function setTotal($total)
        {
            $this->total=$total;
        }
        public function setDelivery($delivery)
        {
            $this->delivery=$delivery;
        }
        public function setPayment($payment)
        {
            $this->payment=$payment;
        }
        public function getName()
        {
            return $this->name;
        }
        public function getAddress()
        {
            return $this->address;
        }
        public function getPhoneNumber()
        {
            return $this->phonenumber;
        }
        public function getIdOrder()
        {
            return $this->idorder;
        }
        public function getIdAcc()
        {
            return $this->idacc;
        }
        public function getDate()
        {
            return $this->date;
        }
        public function getTotal()
        {
            return $this->total;
        }
        public function getDelivery()
        {
            return $this->delivery;
        }
        public function getPayment()
        {
            return $this->payment;
        }
        public function getNumberofPurchased()
        {
            return $this->numberofpurchased;
        }
        public static function getNewId()
        {
            try{
                $sql="call sp_getNewIDOrder;";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                    return $data[0]["ID"];
                return null;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function insertNewOrder()
        {
            try{
                $sql="INSERT INTO `orders` values('$this->idorder','$this->idacc','$this->date','$this->total','$this->delivery','$this->payment','$this->address','$this->name','$this->phonenumber')";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function updateOrder($idorder,$date,$payment,$total)
        {
            try{
                $sql="UPDATE `orders` SET `Date` = '$date', `Payment` = '$payment',`Total`='$total' WHERE `orders`.`IdOrder` = '$idorder';";
                echo '<script>alert("'.$sql.'")</script>';
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
        public function updateDeliveryInfo($idorder,$name,$address,$phonenumber)
        {
            try{
                $sql="UPDATE `orders` SET `Name` = '$name', `Delivery` = 'CONFIRMED',`Address`='$address',`PhoneNumber`='$phonenumber' WHERE `orders`.`IdOrder` = '$idorder';";
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
        public static function checkExisted($idorder)
        {
            try{
                $sql="SELECT * FROM orders WHERE idorder='".$idorder."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $order=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($order))
                    return 1;
                return 0;
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function selectAllOrdersByIdUser($id)
        {
            try{
                $sql="SELECT * FROM orders WHERE idacc='".$id."' ORDER BY date DESC";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $orders=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($orders))
                {
                    $list=array();
                    foreach($orders as $item)
                    {
                        $order=new Orders($item['IdOrder'],$item['IdAcc'],$item['Total'],$item['Name'],$item['PhoneNumber'],$item['Address']);
                        $order->setDate($item['Date']);
                        $order->setPayment($item['Payment']);
                        $order->setDelivery($item['Delivery']);
                        $list[]=$order;
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
        public static function deleteListOrder($id)
        {
            try{
                $sql="DELETE FROM `orders` WHERE `orders`.`IdAcc`='$id';";
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
        public static function deleteSpecificOrder($idorder)
        {
            try{
                $sql="DELETE FROM `orders` WHERE `orders`.`IdOrder`='$idorder';";
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

        public static function getTotalOrder($search)
        {
            try{
                $sql="Select count(*) as `total` from orders";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database'.$sql.'")</script>';
                else
                    return $data[0];
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function getOrdersPerPage($offset,$limit,$search)
        {
            try{
                $sql="Select idorder,idacc,total,date from orders";
                $sql.=" where `orders`.delivery!='IN PROGRESS'";
                if(trim($search)!="")
                {
                    switch($search)
                    {
                        case 'totalasc':
                            {
                                $sql.=' ORDER BY total ASC';
                                break;
                            }
                        case 'totaldesc':
                            {
                                $sql.=' ORDER BY total DESC ';
                                break;
                            }
                        case 'dateasc':
                            {
                                $sql.=' ORDER BY date ASC';
                                break;
                            }
                        case 'datedesc':
                            {
                                $sql.=' ORDER BY date DESC';
                                break;
                            }
                    }
                }
                $sql.=" LIMIT :limit OFFSET :offset";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset,PDO::PARAM_INT);
                
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    $listorder=array();
                    foreach($data as $item)
                    {
                        $order=new Orders($item['idorder'],$item['idacc'],$item['total'],null,null,null);
                        $order->setDate($item['date']);
                        $listorder[]=$order;
                    }
                    return $listorder;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
            }
        }
        public function selecSpecificOrder($id)
        {
            try{
                $sql="SELECT idorder,idacc,total,name,phonenumber,address,date,payment,delivery,(SELECT SUM(orderdetails.Quantity) from orders,orderdetails where orders.IdOrder='$id' and orderdetails.IdOrder=orders.IdOrder)as `purchased` FROM orders WHERE idorder='".$id."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $orders=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($orders))
                {
                    $item=$orders[0];
                    $order=new Orders($item['idorder'],$item['idacc'],$item['total'],$item['name'],$item['phonenumber'],$item['address']);
                    $order->setDate($item['date']);
                    $order->setPayment($item['payment']);
                    $order->setDelivery($item['delivery']);
                    $order->setNumberofPurchased($item['purchased']);
                    return $order;
                }
                return null;
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
    }