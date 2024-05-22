<?php
    class Account{
        private $idacc;
        private $username;
        private $password;
        private $email;
        private $phonenumber;
        private $gender;
        private $address;
        private $totalspent;
        private $avatar;
        private $typeacc;
        private $receivername;
        private $token;
        private $expireddate;
        private $numberofpurchased;
        public function __construct(?string $idacc = null,?string $username = null, ?string $password = null,?string $email = null,?string $phonenumber = null,?string $gender = null,?string $avatar = null,?string $address = null,?float $totalspent=0,?string $typeacc=null,?string $receivername=null) {
            $this->idacc=$idacc;
            $this->username = $username;
            $this->password = $password;
            $this->email=$email;
            $this->phonenumber=$phonenumber;
            $this->gender=$gender;
            $this->address=$address;
            $this->totalspent=$totalspent;
            $this->typeacc=$typeacc;
            $this->receivername=$receivername;
            $this->avatar=$avatar;
            $this->token=null;
            $this->expireddate=null;
        }
        public function setNumberOfPurchased($numberofpurchased)
        {
            $this->numberofpurchased=$numberofpurchased;
        }
        public function setToken($token)
        {
            $this->token=$token;
        }
        public function setExpiredDate($expireddate)
        {
            $this->expireddate=$expireddate;
        }
        public function setAvatar($avatar)
        {
            $this->avatar=$avatar;
        }
        public function setIdAcc($idacc)
        {
            $this->idacc=$idacc;
        }
        public function setUsername($username)
        {
            $this->username=$username;
        }
        public function setPassword($password)
        {
            $this->password=$password;
        }
        public function setEmail($email)
        {
            $this->email=$email;
        }
        public function setPhoneNumber($phonenumber)
        {
            $this->phonenumber=$phonenumber;
        }
        public function setGender($gender)
        {
            $this->gender=$gender;
        }
        public function setAddress($address)
        {
            $this->address=$address;
        }
        public function setTotalSpent($totalspent)
        {
            $this->totalspent=$totalspent;
        }
        public function setTypeAcc($typeacc)
        {
            $this->typeacc=$typeacc;
        }
        public function setReceiverName($receivername)
        {
            $this->receivername=$receivername;
        }
        public function getToken()
        {
            return $this->token;
        }
        public function getExpiredDate()
        {
            return $this->expireddate;
        }
        public function getAvatar()
        {
            return $this->avatar;
        }
        public function getIdAcc()
        {
            return $this->idacc;
        }
        public function getUsername()
        {
            return $this->username;
        }
        public function getPassword()
        {
            return $this->password;
        }
        public function getEmail()
        {
            return $this->email;
        }
        public function getPhoneNumber()
        {
            return $this->phonenumber;
        }
        public function getGender()
        {
            return $this->gender;
        }
        public function getAddress()
        {
            return $this->address;
        }
        public function getTotalSpent()
        {
            return $this->totalspent;
        }
        public function getTypeAcc()
        {
            return $this->typeacc;
        }
        public function getReceiverName()
        {
            return $this->receivername;
        }
        public function getNumberOfPurchased()
        {
            return $this->numberofpurchased;
        }
        public static function getNewId()
        {
            try{
                $sql="call `sp_getNewIDAcc`";
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
        public function getDeliveryInfo($id)
        {
            try
            {
                $sql="SELECT receivername, phonenumber,address FROM account WHERE idacc=:idacc";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':idacc',$id,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user))
                {
                    $output=new Account(null,null,null,null,$user[0]['phonenumber'],null,null,$user[0]['address'],0,null,$user[0]['receivername']);
                    return $output;
                }
                return null;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function insert_Account()
        {
            try
            {
                $email=$number='NULL';
                if($this->phonenumber!=NULL)
                    $number="'".$this->phonenumber."'";
                if($this->email!=NULL)
                    $email="'".$this->email."'";
                $this->password=password_hash($this->password,PASSWORD_DEFAULT);
                $sql="INSERT INTO account
                VALUES ('$this->idacc', '$this->username',$email,$number,'$this->password','$this->gender',NULL,NULL,0,'$this->typeacc',NULL,NULL,NULL);";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function auto_create_Account()
        {
            try
            {
                $this->password=password_hash($this->password,PASSWORD_DEFAULT);
                $sql="INSERT INTO account
                VALUES ('$this->idacc', '$this->username',NULL,'$this->phonenumber','$this->password','$this->gender',NULL,'$this->address',0,'$this->typeacc','$this->receivername',NULL,NULL);";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                return 1;
            }
            catch(PDOException $ex){
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function validateNumber($id,$number)
        {
            try{
                $sql="SELECT idacc FROM account WHERE phonenumber=:number";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':number',$number,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user[0]))
                {
                    if($id==$user[0]['idacc'])
                        return true;
                    else
                        return false;
                }
                else
                    return true;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function customValidationRow($row,$data)
        {
            try{
                $sql="SELECT * FROM account WHERE ".$row."='".$data."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($user[0]))
                    return true;
                else
                    return false;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function validateUsername()
        {
            try{
                $sql="SELECT * FROM account WHERE name=:name";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':name',$this->username,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($user[0]))
                    return true;
                else
                    return false;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function validateEmail()
        {
            try{
                $sql="SELECT * FROM account WHERE email=:email";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':email',$this->email,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($user[0]))
                    return true;
                else
                    return false;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function validatePassword()
        {
            try{
                $sql="SELECT password FROM account WHERE name=:name or email=:email ";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':email',$this->username,PDO::PARAM_STR);
                $stmt->bindParam(':name',$this->username,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                if(password_verify($this->password,$user['password'])==true)
                    return true;
                return false;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public static function getUserdataInDatabase($data,$output)
        {
            try{
                $sql="SELECT ".$output." FROM account WHERE name='".$data."' or email ='".$data."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user))
                    return $user[0];
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function updateDeliveryInfo($idacc,$name,$address,$phonenumber,$total)
        {
            try{
                $sql="UPDATE `account` SET ";
                $updateColumn="";
                if(!empty($name))
                    $updateColumn.="`ReceiverName` = '$name' ,";
                if(!empty($address))
                    $updateColumn.="`Address`='$address' ,";
                if(!empty($phonenumber))
                    $updateColumn.="`PhoneNumber`='$phonenumber' ,";
                $sql.=$updateColumn." `TotalSpent`= `TotalSpent`+ $total  WHERE `account`.`IdAcc` = '$idacc'";
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
        public function updateInfo($idacc,$name,$address,$phonenumber)
        {
            try{
                $sql="UPDATE `account` SET ";
                $updateColumn="";
                if(!empty($name))
                {
                    $updateColumn.="`ReceiverName` = '$name'";
                }
                if(!empty($address))
                {
                    if(!empty($name))
                        $updateColumn.=", ";
                    $updateColumn.="`Address`='$address'";
                }
                if(!empty($phonenumber))
                {
                    if(!empty($address))
                        $updateColumn.=", ";
                    $updateColumn.="`PhoneNumber`='$phonenumber' ";
                }
                $sql.=$updateColumn." WHERE `account`.`IdAcc` = '$idacc'";
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
        public static function checkExisted($idacc)
        {
            try{
                $sql="SELECT * FROM account WHERE idacc='".$idacc."'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user))
                    return 1;
                return 0;
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function getIdAccByNumber($phonenumber)
        {
            try{
                $sql="SELECT idacc FROM account WHERE phonenumber=:phonenumber";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':phonenumber',$phonenumber,PDO::PARAM_STR);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user))
                    return $user[0]['idacc'];
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function checkUsernameAndEmail($username,$email)
        {
            try{
                $sql="select idacc from account where account.Name ='$username' and account.Email= LOWER('$email') and account.TypeAcc!='ADMIN' and account.TypeAcc!='BANNED'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($user[0]))
                    return null;
                else
                    return $user[0]['idacc'];
            }
            catch(PDOException $ex)
            {
                    echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function updateToken()
        {
            try{
                $sql="UPDATE `account` SET `Token`='$this->token', `ExpiredDate`='$this->expireddate' WHERE `IdAcc`='$this->idacc'";
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
        public function checkValidToken($id,$token)
        {
            try{
                $sql="SELECT expireddate from account where token='$token' and idacc='$id'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($data))
                {
                    $user=new Account();
                    $user->setExpiredDate($data[0]['expireddate']);
                    $date= strtotime($user->expireddate);
                    if(date("Y-m-d H:i:s")>date('Y-m-d H:i:s', $date))
                    {
                        echo '<script>alert("Token is expired!")</script>';
                        $sql="UPDATE `account` set `Token`='NULL',`ExpiredDate`=NULL where idacc=:id";
                        $stmt=Database::$pdo->prepare($sql);
                        $stmt->bindParam(':id',$id,PDO::PARAM_STR);
                        $stmt->execute();
                        return null;
                    }
                    else
                    {
                        return $user;
                    }
                }
                else
                {
                    echo '<script>alert("Invalid token!")</script>';
                    return null;
                }
                
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }
        }
        public function updatePass($pass,$id)
        {
            try{
                $sql="UPDATE `account` set `password` =:pass , `Token`='NULL',`ExpiredDate`= NULL where `idacc`=:id ";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':id',$id,PDO::PARAM_STR);
                $stmt->bindParam(':pass',password_hash($pass,PASSWORD_DEFAULT),PDO::PARAM_STR);
                $stmt->execute();
                return 1;
            }catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function getUsersPerPage($offset,$limit,$search)
        {
            try{
                $sql="Select idacc,name,email,phonenumber,typeacc,totalspent,avatar from account";
                if(trim($search)!="")
                    $sql.=" where  typeacc LIKE '%$search%' or idacc LIKE '%$search%' or name LIKE '%$search%' or phonenumber LIKE '%$search%' or email LIKE '%$search%' ";
                $sql.=" ORDER by idacc LIMIT :limit OFFSET :offset";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':limit',$limit,PDO::PARAM_INT);
                $stmt->bindParam(':offset',$offset,PDO::PARAM_INT);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    $listacc=array();
                    foreach($data as $item)
                    {
                        $acc=new Account($item['idacc'],$item['name'],NULL,$item['email'],$item['phonenumber'],NULL,$item['avatar'],NULL,$item['totalspent'],$item['typeacc'],null);
                        $listacc[]=$acc;
                    }
                    return $listacc;
                }
                   
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
            }
        }
        public function getUserById($id)
        {
            try{
                $item=NULL;
                $sql="Select idacc,name,email,phonenumber,gender,address,typeacc,totalspent,(SELECT SUM(orderdetails.Quantity) from orders,orderdetails where orders.IdAcc='$id' and orderdetails.IdOrder=orders.IdOrder)as `purchased`,receivername from account where idacc= '$id'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(empty($data))
                    echo '<script>alert("There was no data in database")</script>';
                else
                {
                    $item=$data[0];
                    $acc=new Account($item['idacc'],$item['name'],NULL,$item['email'],$item['phonenumber'],$item['gender'],NULL,$item['address'],$item['totalspent'],$item['typeacc'],$item['receivername']);
                    if(empty($item['purchased']))
                        $item['purchased']=0;
                    $acc->setNumberOfPurchased($item['purchased']);
                    return $acc;
                }
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
            }
        }
        public static function getTotalAccount($search)
        {
            try{
                $sql="Select count(*) as total, SUM(IF(account.TypeAcc='ADMIN', 1, 0)) AS `admin`,  SUM(IF(account.TypeAcc='CUSTOMER', 1, 0)) AS `customer`,SUM(IF(account.TypeAcc='GUEST', 1, 0)) AS `guest` from account";
                if(trim($search)!="")
                    $sql.=" where typeacc LIKE '%$search%' or idacc LIKE '%$search%' or name LIKE '%$search%' or phonenumber LIKE '%$search%' or email LIKE '%$search%' ";
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
                echo '<script>alert("'.$ex->getMessage().$sql.'")</script>';
            }
        }
        public function updateTypeAcc($type,$id)
        {
            try{
                $sql="UPDATE `account` set `typeacc` =:type where `idacc`=:id ";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':id',$id,PDO::PARAM_STR);
                $stmt->bindParam(':type',$type,PDO::PARAM_STR);
                $stmt->execute();
                return 1;
            }catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return 0;
            }
        }
        public function validateBannedUser()
        {
            try{
                $sql="SELECT idacc FROM account WHERE (name='$this->username'or email='$this->username') and typeacc='BANNED' ";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $user=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($user[0]))
                    return true;
                else
                    return false;
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function deleteUser($id)
        {
            try{
                $sql="DELETE FROM `account` where idacc='$id'";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->execute();
                $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
            }
        }
        public function auto_create_Account_admin()
        {
            try
            {
                $this->password=password_hash($this->password,PASSWORD_DEFAULT);
                $sql="INSERT INTO account
                VALUES ('$this->idacc', '$this->username','$this->email','$this->phonenumber','$this->password','$this->gender',NULL,'$this->address',0,'$this->typeacc','$this->receivername',NULL,NULL);";
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