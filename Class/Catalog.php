<?php
    class Catalog{
            private $idcatalog;
            private $namecatalog;
            public function __construct(?string $idcatalog = null,?string $namecatalog = null) {
                $this->idcatalog=$idcatalog;
                $this->namecatalog = $namecatalog;
            }
            public function setIdCatalog($idcatalog)
            {
                $this->idcatalog=$idcatalog;
            }
            public function setNameCatalog($namecatalog)
            {
                $this->namecatalog=$namecatalog;
            }
            public function getIdCatalog()
            {
                return $this->idcatalog;
            }
            public function getNameCatalog()
            {
                return $this->namecatalog;
            }
            public static function getAllCatalogs()
            {
                try{
                    $sql="call sp_selectAllCatalogs";
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->execute();
                    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(empty($data))
                        echo '<script>alert("Table is empty!")</script>';
                    else
                    {
                        $listcatalogs = array();
                        foreach($data as $card)
                        {
                            $catalog =new Catalog();
                            $catalog ->setIdCatalog($card['IdCatalog']);
                            $catalog ->setNameCatalog($card['NameCatalog']);
                            $listcatalogs[]=$catalog ;
                        }
                        return $listcatalogs;
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
                    $sql="select idcatalog from catalog where LOWER(namecatalog) = lower('$name')";
                    $stmt=Database::$pdo->prepare($sql);
                    $stmt->execute();
                    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(empty($data))
                        echo '<script>alert("Catalog does not exist!"")</script>';
                    else
                    {
                        return $data[0];
                    }
                       
                }
                catch(PDOException $ex)
                {
                    echo '<script>alert("'.$ex->getMessage().'")</script>';
                }
                return null;
            }
        }?>