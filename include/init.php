<?php
    require '../config.php';
    spl_autoload_register(function($className){
        require '../Class/'.$className.'.php';
    });
    ob_start();
    session_start();
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    Database::getConnection();
    $file = basename($_SERVER['PHP_SELF']);
    if($file!="changeproductinfoform.php"&&isset($_SESSION['confirmeddelete']))
        unset($_SESSION['confirmeddelete']);
    if($file!="productmanagement.php"&&isset($_SESSION['idproductmanagement']))
        unset($_SESSION['idproductmanagement']);
    if($file!="usermanagement.php"&&isset($_SESSION['idusermanagement']))
        unset($_SESSION['idusermanagement']);
    if($file!="ordermanagement.php"&&isset($_SESSION['idordermanagement']))
        unset($_SESSION['idordermanagement']);
        
        
        

