<?php 
    class ImageDetails{
        private $idimage;
        private $idproduct;
        private $imagetype;
        public function __construct(?string $idimage = null,?string $idproduct = null,?string $imagetype = null) {
            $this->idimage=$idimage;
            $this->idproduct = $idproduct;
            $this->imagetype = $imagetype;
        }
        public function setIdImage($idimage)
        {
            $this->idimage=$idimage;
        }
        public function setIdProduct($idproduct)
        {
            $this->idproduct=$idproduct;
        }
        public function setImageType($imagetype)
        {
            $this->imagetype=$imagetype;
        }
        public function getIdImage()
        {
            return $this->idimage;
        }
        public function getIdProduct()
        {
            return $this->idproduct;
        }
        public function getImageType()
        {
            return $this->imagetype;
        }
        public function insert_ImageDetails()
        {
            try
            {
                $sql="INSERT INTO imagedetails
                VALUES ('$this->idimage', '$this->idproduct','$this->imagetype');";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return true;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public  function getIdPostImageByIdProduct($idpro)
        {
            try
            {
                $sql="SELECT imagedetails.IdImage FROM imagedetails, product where product.IdProduct=imagedetails.IdProduct and imagedetails.ImageType='POST' and product.IdProduct='$idpro'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                    return $data[0]["IdImage"];
                return null;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        
        
    }