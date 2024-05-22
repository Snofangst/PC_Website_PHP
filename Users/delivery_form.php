<title>DELIVERY FORM - ZEUS'S PC</title>
<?php
    require '../include/init.php';
    require '../include/header.php';
    
    $NumberError=null;
    $NameError=null;
    $AddressError=null;
    $IdAccError=null;
    $IdOrderError=null;
    $ProductError=null;
    $IdAcc=null;
    $nameFill=$phoneFill=$addressFill=null;
    if(isset($_SESSION['idorder'])&&isset($_SESSION['login']))
    {
        $account=new Account();
        $account=$account->getDeliveryInfo($_SESSION['login'][0]);
        if(empty($account))
            Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif','Something went wrong!');
        else
        {
            $nameFill=$account->getReceiverName();
            $phoneFill=$account->getPhoneNumber();
            $addressFill=$account->getAddress();
        }
    } 
    else if(!isset($_SESSION['information']))
        header("location: ../Users/cart.php");
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"]))
    {
        if(isset($_SESSION['login'])&&Account::checkExisted($_SESSION['login'][0])==0)
            $IdAccError="Account has been deleted!";
        else if(isset($_SESSION['idorder'])&&Orders::checkExisted($_SESSION['idorder'])==0)
            $IdOrderError="Order has been deleted!";
        else
        {
            $listItems=$_SESSION['listitems'];
            $totalProducts=count($listItems);
            $countProduct=0;
            foreach($listItems as $Item)
            {
                if(Product::checkExistedandAvailable($Item->getIdProduct(),$Item->getQuantity())==1)
                    $countProduct++;
            }
            if($totalProducts!=$countProduct)
                $ProductErrorError="Product isn't existed anymore or product quantity in database is lower than in cart !";
        }
        if(!empty($IdAccError)&&!empty($IdOrderError)&&!empty($ProductError))
            Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$IdAccError.$IdOrderError.$ProductError);
        else
        {
            
            $length=strlen($_POST['phonenumber']);
            if(!is_numeric($_POST['phonenumber']))
                $NumberError="Your phone number can't contain alphabets!";
            else if($length>11||$length<10)
                $NumberError="Your phone number must be 10 to 11 digits!";
            else if(isset($_SESSION['login'])&&$account->validateNumber($_SESSION['login'][0],$_POST['phonenumber'])==false)
                $NumberError="This phone number has been taken!";
            if(strlen(trim($_POST['name'])) == 0)
                $NameError="Your name can't be blank!";
            if(strlen(trim($_POST['address'])) == 0)
                $AddressError="Your address can't be blank!";
            if(empty($NumberError)&&empty($NameError)&&empty($AddressError))
            {
                try{
                    $name=$address=$phonenumber=null;
                    if(isset($_SESSION['login']))
                    {
                        $order=new Orders();
                        $order->updateDeliveryInfo($_SESSION['idorder'],$_POST['name'],$_POST['address'],$_POST['phonenumber']);
                        if($account->getReceiverName()!=$_POST['name'])
                            $name=$_POST['name'];
                        if($account->getPhoneNumber()!=$_POST['phonenumber'])
                            $phonenumber=$_POST['phonenumber'];
                        if($account->getAddress()!=$_POST['phonenumber'])
                            $address=$_POST['address'];
                        if(!empty($name)||!empty($address)||!empty($phonenumber))
                            $account->updateDeliveryInfo($_SESSION['login'][0],$name,$address,$phonenumber,$_SESSION['total']);
                        foreach($listItems as $item)
                        {
                            $product=new Product();
                            $product->updateQuantity($item->getIdProduct(),$item->getQuantity());
                        }
                        header("location:../Home/index.php");
                        $_SESSION['success_purchase']=1;
                    }
                    else if(isset($_SESSION['information']))
                    {
                        $IdOrder=null;
                        $information=$_SESSION['information'];
                        $guest=new Account();
                        $IdAcc=$guest->getIdAccByNumber($_POST['phonenumber']);
                        if(empty($IdAcc))
                        {
                            $errorCount=0;
                            $accinserted=0;
                            do{
                                try{
                                    $IdAcc=Account::getNewId();
                                    $account=new Account($IdAcc,$_POST['phonenumber'],Method::getRandomPassword(15),NULL,$_POST['phonenumber'],'UD',null,$_POST['address'],0,'GUEST',$_POST['name']);
                                    if($account->auto_create_Account()==1)
                                        $accinserted=1;
                                    else
                                        $errorCount++;
                                }
                                catch(Exception $ex){
                                }

                            }while($accinserted==0&&$errorCount<10);
                        }
                        $inserted=0;
                        do{
                            try{
                                $IdOrder=Orders::getNewId();
                                $order=new Orders($IdOrder,$IdAcc,$information[1],$_POST['name'],$_POST['phonenumber'],$_POST['address']);
                                $order->setPayment($information[0]);
                                $order->setDelivery('CONFIRMED');
                                $order->insertNewOrder();
                                foreach($listItems as $item)
                                {
                                    $detail=new OrderDetails($IdOrder,$item->getIdProduct(),$item->getQuantity(),$item->getPrice(),$item->getDiscount());
                                    $detail->insertOrderDetail();
                                    $product=new Product();
                                    $product->updateQuantity($item->getIdProduct(),$item->getQuantity());
                                }
                                $inserted=1;
                            }
                            catch(Exception $ex){}
                        }while($inserted==0);
                        if($inserted==1)
                        {
                            $guest->updateDeliveryInfo($IdAcc,'','','',$information[1]);
                            setcookie('cart',null, time() + (86400 * 30),"/");
                            header("location:../Home/index.php");
                            $_SESSION['success_purchase']=1;
                        }
                    }
                }
                catch(Exception $ex)
                {
                    Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif','Oops! Something went wrong');
                }
            }
        }
    }
?>
<link rel="stylesheet" href="../Style/Delivery_Form.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
<section class="u-clearfix u-section-1" id="sec-fb18">
    <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-grey-light-2 u-hidden-md u-hidden-sm u-hidden-xs u-map u-map-1">
            <div class="embed-responsive">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0672956111666!2d106.62646731474928!3d10.806157892301389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27d8b4f4d%3A0x92dcba2950430867!2sHCMC%20University%20of%20Food%20Industry!5e0!3m2!1sen!2s!4v1670159369987!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <h1 class="u-custom-font u-font-spy-agency u-text u-text-default u-text-1 "></h1>
        <div class="u-expanded-width-xs u-form u-form-1" >
            <form method="post">
                <div class="u-clearfix u-form-spacing-20 u-form-vertical u-inner-form" style="padding: 10px" source="email" name="form">
                    <div class="u-form-group u-form-name u-label-top u-custom-font u-font-spy-agency">
                        <label style=" color:white" class="u-label">Name (*)</label>
                        <span style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$NameError?></span>
                        <input onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} else  if(!empty($account)) echo $account->getReceiverName();  else echo ""?>" required class=" u-custom-font u-font-oswald u-border-2 u-border-black u-input u-input-rectangle u-radius-10 u-input-1" id="name-3b9a" name="name" placeholder="Enter your name" type="text" value="" required/>
                    </div>
                    <div class="u-form-email u-form-group u-label-top u-custom-font u-font-spy-agency">
                        <label style="color:white" class="u-label">Phone number (*)</label>
                        <span style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$NumberError?></span>
                        <input value="<?php if(isset($_POST['phonenumber'])){echo $_POST['phonenumber'];}else if(!empty($account)) echo $account->getPhoneNumber(); else echo ""; ?>" required class=" u-custom-font u-font-oswald u-border-2 u-border-black u-input u-input-rectangle u-radius-10 u-input-2" id="email-3b9a" name="phonenumber" placeholder="Enter your phone number" type="text" value=""/>
                    </div>
                    <div class="u-form-group u-form-message u-label-top u-custom-font u-font-spy-agency">
                        <label style="color:white" class="u-label">Address (*)</label>
                        <span style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$AddressError?></span>
                        <textarea onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" style="resize: none" required class=" u-custom-font u-font-oswald u-border-2 u-border-black u-input u-input-rectangle u-radius-10 u-input-3" cols="50"   placeholder="Enter your address" id="message-3b9a" name="address" rows="4"><?php if(isset($_POST['address'])){echo $_POST['address'];} else  if(!empty($account)) echo $account->getAddress(); else echo "";?></textarea>
                    </div>
                    <div class="u-align-center u-form-group u-form-submit u-label-top">
                        <a href="#" class="u-custom-font u-font-spy-agency u-border-2 u-border-active-black u-border-white u-border-hover-palette-5-base u-btn u-btn-round u-btn-submit u-button-style u-white u-radius-10 u-btn-1">Submit</a>
                        <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    function checkQuote() {
        if(event.keyCode == 39 || event.keyCode == 34) {
            event.keyCode = 0;
            return false;
        }
    }
</script>
<?php require '../include/footer.php' ?>