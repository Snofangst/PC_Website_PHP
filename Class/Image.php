<?php
    class Image{
        private $idimage;
        private $filename;
        public function __construct(?string $idimage = null,?string $filename = null) {
            $this->idimage=$idimage;
            $this->filename = $filename;
        }
        public function setIdImage($idimage)
        {
            $this->idimage=$idimage;
        }
        public function setFileName($filename)
        {
            $this->filename=$filename;
        }
        public function getIdImage()
        {
            return $this->idimage;
        }
        public function getFileName()
        {
            return $this->filename;
        }
       
        public function insert_Image()
        {
            try
            {
                $sql="INSERT INTO image
                VALUES (NULL, '$this->filename');";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return true;
            }
            catch(PDOException $ex){
                return false;
            }
        }
        public static function getNewId()
        {
            try{
                $sql="call sp_getNewIDImage;";
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
        public static function getDefaultImage($fileimage)
        {
            if($fileimage==NULL)
                return "../Images/Products/Error.png";
            else
                return $fileimage;
        }
        public function getAllImageByIdProduct($id)
        {
            try{
                $sql="select image.IdImage,image.FileImage from image,imagedetails where imagedetails.IdProduct=:idproduct and imagedetails.IdImage=image.IdImage";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':idproduct',$id,PDO::PARAM_STR);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                {
                    $listimage=array();
                    foreach($data as $item)
                    {
                        $image=new Image($item['IdImage'],$item['FileImage']);
                        $listimage[]=$image;
                    }
                    return $listimage;
                }
                return null;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function deleteImage($idimage,$fileimage)
        {
            try{
                $sql="Delete from `image` where `idimage`=:id";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':id',$idimage,PDO::PARAM_STR);
                $stmt->execute();
                unlink($fileimage);
                return 1;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function updateImage()
        {
            try
            {
                $sql="UPDATE `image` SET `FileImage`='$this->filename' WHERE `IdImage`='$this->idimage'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
    }
    

?>