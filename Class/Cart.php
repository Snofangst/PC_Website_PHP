<?php
    class Cart extends Product{
        private $max;
        private $priceafterdiscount;
        public function __construct( ?string $idproduct = null,?string $nameproduct = null,?string $image=null,?float $price = 0.0,?int $quantity = 0,?int $max=0,?float $discount=0.0) {
            $this->idproduct=$idproduct;
            $this->nameproduct = $nameproduct;
            $this->image=$image;
            $this->price=$price;
            $this->quantity=$quantity;
            $this->max=$max;
            $this->discount=$discount;
        }
        public function setMax($max)
        {
            $this->max=$max;
        }
        public function getMax()
        {
            return $this->max;
        }
       
        public static function getItemsFromCartByAccID($id)
        {
            try
            {
                $sql="call sp_selectItemsInCart('$id')";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                $list=array();
                foreach($data as $item)
                {
                    $list[]=new Cart($item['idproduct'],$item['nameproduct'],Image::getDefaultImage($item['fileimage']),$item['price'],$item['quantity'],$item['max'],$item['discount']);
                }
                return $list;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getItemsFromCookie($cookie)
        {
            try
            {
                $productids="";
                foreach($cookie as $item)
                {
                    $productids.="'".$item->getIdProduct()."'";
                    if(end($cookie)!=$item)
                        $productids.=',';
                }
                $sql="SELECT product.idproduct, nameproduct, price, fileimage, product.quantity as max,product.discount from product"
                ." left join imagedetails on product.IdProduct=imagedetails. IdProduct and ImageType LIKE '%POST%'"
                ." left join image on image.Idimage=imagedetails. Idimage"
                ." where product.idproduct in(".$productids.");";
               
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                $list=array();
                foreach($data as $item)
                {
                    $quantity=1;
                    foreach($cookie as $i)
                    {
                        if($i->getIdProduct()==$item['idproduct'])
                        {
                            $quantity=$i->getQuantity();
                            break;
                        }
                    }
                    $list[]=new Cart($item['idproduct'],$item['nameproduct'],Image::getDefaultImage($item['fileimage']),$item['price'],$quantity,$item['max'],$item['discount']);
                }
                return $list;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        
    }