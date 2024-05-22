<?php
     Class Database
     {
        private static $db_host=DB_HOST;
        private static $db_name=DB_NAME;
        private static $db_user=DB_USER;
        private static $db_pass=DB_PASS;
        public static $dsn=null;
        public static $pdo= null;
        public static function getConnection()
        {
             try{
                 Database::$dsn="mysql:host=".Database::$db_host.";dbname=".Database::$db_name.";charset=utf8mb4";
                 Database::$pdo=new PDO(Database::$dsn,Database::$db_user,Database::$db_pass);
                 Database::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                 Database::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                 return Database::$pdo;
             }
             catch(PDOException $ex)
             {
                echo '<script>alert("'.$ex->getMessage().Database::$dsn.'")</script>';
             }
        }
        public static function getIdOrderInProgressByAccId($id)
        {
            try
            {
                $sql="SELECT orders.idorder from account"
                ." right join orders on account.IdAcc=orders.IdAcc and orders.Delivery='IN PROGRESS'"
                ." where account.IdAcc LIKE '".$id."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $idorder=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if($idorder!=null)
                    return $idorder[0]['idorder'];
                return $idorder;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public static function getAvailableCartByIdAcc($id)
        {
            try{
                $sql="SELECT * from orders where idacc='".$id."' and orders.delivery='IN PROGRESS'";
                $stmt=Database::$pdo->perpare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        
    }