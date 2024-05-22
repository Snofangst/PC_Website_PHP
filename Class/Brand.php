<?php
    class Brand{
        private $idbrand;
        private $namebrand;
        public function __construct(?string $idbrand = null,?string $namebrand = null) {
            $this->idbrand=$idbrand;
            $this->namebrand = $namebrand;
        }
        public function setIdBrand($idbrand)
        {
            $this->idbrand=$idbrand;
        }
        public function setNameBrand($namebrand)
        {
            $this->namebrand=$namebrand;
        }
        public function getIdBrand()
        {
            return $this->idbrand;
        }
        public function getNameBrand()
        {
            return $this->namebrand;
        }
        public static function getAllBrands()
        {
            try{
                $sql="call sp_selectAllBrands";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("Table is empty!")</script>';
                else
                {
                    $listbrands = array();
                    foreach($data as $card)
                    {
                        $brand =new Brand();
                        $brand->setIdBrand($card['IdBrand']);
                        $brand->setNameBrand($card['NameBrand']);
                        $listbrands[]=$brand;
                    }
                    return $listbrands;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function getIdByName($name)
        {
                try{
                    $sql="select idbrand from brand where LOWER(namebrand) = lower('$name')";
                    
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->execute();
                    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!empty($data))
                        return $data[0];
                       
                }
                catch(PDOException $ex)
                {
                    echo '<script>alert("'.$ex->getMessage().'")</script>';
                }
                return null;
        }
    }?>