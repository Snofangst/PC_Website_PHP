<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    $RecipientFill=$PhoneNumberFill=$AddressFill=NULL;
    $NumberError=$AddressError=$NameError=NULL;
    if(isset($_GET['id']))
    {
        $acc=new Account();
        if(Account::checkExisted($_GET['id'])==1)
        {
            
            $acc=$acc->getDeliveryInfo($_GET['id']);
            $RecipientFill=$acc->getReceiverName();
            $PhoneNumberFill=$acc->getPhoneNumber();
            $AddressFill=$acc->getAddress();
        }
        else
            header("location: ../Admin/Error.php");
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            $length=strlen($_POST['phonenumber']);
            if(!is_numeric($_POST['phonenumber']))
                $NumberError="Customer's phone number can't contain alphabets!";
            else if($length>11||$length<10)
                $NumberError="Customer's phone number must be 10 to 11 digits!";
            else if(isset($_GET['id'])&&$acc->validateNumber($_GET['id'],$_POST['phonenumber'])==false)
                $NumberError="This phone number has been taken!";
            if(strlen(trim($_POST['name'])) == 0)
                $NameError="Customer's name can't be blank!";
            if(strlen(trim($_POST['address'])) == 0)
                $AddressError="Customer's address can't be blank!";
            if(empty($NumberError)&&empty($AddressError)&empty($NameError))
            {
                $acc->updateInfo($_GET['id'],$_POST['name'],$_POST['address'],$_POST['phonenumber']);
                Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Successfully updated!");
            }
            else{
                $warning=null;
                if(!empty($NumberError)) {
                    $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                        '.$NumberError.'
                    </li>';
                }
                if(!empty($AddressError)) { 
                    $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                        '.$AddessError.'
                    </li>';
                }
                if(!empty($NameError)) { 
                    $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                        '.$NameError.'
                    </li>';
                }
                Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif','Something went wrong!'.' <ul style="margin-left:20px;margin-top:5px">'.$warning.'</ul>');
               
            }
        }
    }
    else
        header("location: ../Admin/Error.php");
?>
<link rel="stylesheet" href="../Style/ChangeUser.css" media="screen">
<section class="u-align-center u-clearfix u-image u-section-1" id="sec-e80e" data-image-width="1920" data-image-height="1006">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-form u-form-1">
          <form method="post" style="padding: 10px" name="form">
            <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form">
                <div class="u-form-group u-form-name u-label-none">
                <input required value="<?=$RecipientFill?>" type="text" placeholder="Enter customer's recipient name" id="name-3b9a" name="name" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="">
                </div>
                <div class="u-form-group u-label-none u-form-group-2">
                <input required value="<?=$PhoneNumberFill?>" type="text" placeholder="Enter customer's phone number" id="text-c5a4" name="phonenumber" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
                </div>
                <div class="u-form-group u-form-textarea u-label-none u-form-group-3">
                <textarea required rows="4" cols="50" id="textarea-4da5" name="address" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="" placeholder="Enter customer's address"><?=$AddressFill?></textarea>
                </div>
                <div class="u-align-center u-form-group u-form-submit u-label-none">
                <a href="#" class="u-active-palette-5-base u-black u-border-2 u-border-grey-75 u-border-hover-black u-btn u-btn-round u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-white u-radius-10 u-text-active-black u-text-hover-black u-text-white u-btn-1">Update</a>
                <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                </div>
            </div>
          </form>
        </div>
        <img class="u-expanded-width-xs u-image u-image-contain u-image-default u-image-1" src="../Images/Website/change-user.png" alt="" data-image-width="1000" data-image-height="346">
      </div>
    </section>
<?php
    require '../include/footer.php';
?>