<?php
    require '../include/init.php';
    require "../include/header_admin.php";
    $UsernameError=$EmailError=$NumberError=$NameError=$AddressError=null;
    $UsernameFill=$EmailFill=$ReceiverNameFill=$PhoneNumberFill=$AddressFill=$TypeFill=$GenderFill=null;
    $user=new Account();
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])){
        $user=new Account(null,$_POST["username"],$_POST["password"],strtolower($_POST["email"]),$_POST['phonenumber'],$_POST["gender"],null,$_POST['address'],0,$_POST['typeacc'],$_POST['receivername']);
        if($user->validateUsername()==false)
            $UsernameError="Username has already been registered!";
        if($user->validateEmail()==false)
            $EmailError="Email has already been taken!";
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL))
            $EmailError = "Invalid email format";
        if (strlen(trim($user->getUsername())) == 0)
            $UsernameError="Username can't be blank!";
        $length=strlen($_POST['phonenumber']);
        if(!is_numeric($_POST['phonenumber']))
            $NumberError="Customer's phone number can't contain alphabets! (A-Z)";
        else if(($length>11||$length<10))
            $NumberError="Customer's phone number must be 10 to 11 digits!";
        else if(!empty($user->getIdAccByNumber($_POST['phonenumber'])))
            $NumberError="This phone number has been taken!";
        if(strlen(trim($_POST['receivername'])) == 0)
            $NameError="Customer's name can't be blank!";
        if(strlen(trim($_POST['address'])) == 0)
            $AddressError="Customer's address can't be blank!";
        if(empty($UsernameError)&&empty($EmailError)&&empty($NumberError)&&empty($NameError)&&empty($AddressError))
        {
            try{
            $user->auto_create_Account_admin();
            $user=new Account();
            Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Successfully created!");
            }
            catch(Exception $ex){
                Method::getPopUpAnnouncement();
            }
        }else
        {
            $warning=null;
            if(!empty($NumberError)) {
                $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                    '.$NumberError.'
                </li>';
            }
            if(!empty($UsernameError)) { 
                $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                    '.$UsernameError.'
                </li>';
            }
            if(!empty($EmailError)) { 
                $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                    '.$EmailError.'
                </li>';
            }
            if(!empty($AddressError)) { 
                $warning.='<li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="text-align:left;color:#d00539;font-weight:bold">
                    '.$AddressError.'
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
?>
<link rel="stylesheet" href="../Style/AddUser.css" media="screen">
<section class="u-align-center u-clearfix u-image u-section-1" id="sec-e80e" data-image-width="1920" data-image-height="1006">
      <div class="u-clearfix u-sheet u-sheet-1">
        <img class="u-image u-image-contain u-image-default u-image-1" src="../Images/Website/create-an-account.png" alt="" data-image-width="1000" data-image-height="346">
        <div class="u-form u-form-1">
          <form method="post"style="padding: 10px"name="form">
          <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" >
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-1">
              <label for="text-085e" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-1">Username (*)</label>
              <input onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" value="<?=$user->getUsername()?>" required type="text" placeholder="Enter a username" id="text-085e" name="username" class="u-white u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-2">
              <label for="select-b2f1" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-2">Type (*)</label>
              <div class="u-form-select-wrapper">
                <select required id="select-b2f1" name="typeacc" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
                  <option <?php if($user->getTypeAcc()=="ADMIN") echo "selected" ?> value="ADMIN" data-calc="">Admin</option>
                  <option <?php if($user->getTypeAcc()=="CUSTOMER") echo "selected" ?>  value="CUSTOMER" data-calc="">Customer</option>
                </select>
                <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve"><polygon class="st0" points="8,12 2,4 14,4 "></polygon></svg>
              </div>
            </div>
            <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-3">
              <label for="text-c71f" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-3">Password (*)</label>
              <input onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" minlength="6" required type="password" placeholder="Enter password" id="text-c71f" name="password" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-4">
              <label for="text-baeb" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-4">Email (*)</label>
              <input onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);"  value="<?=$user->getEmail()?>" required type="email" placeholder="Enter an email" id="text-baeb" name="email" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-group u-form-input-layout-horizontal u-form-radiobutton u-label-top u-form-group-5">
              <label class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-5">Gender (*)</label>
              <div class="u-form-radio-button-wrapper">
                <div class="u-input-row">
                  <input <?php if($user->getGender()=="M") echo 'checked' ?> required id="field-f251" type="radio" name="gender" value="M" class="u-field-input"  data-calc="M">
                  <label class="u-custom-font u-field-label u-font-oswald u-text-body-alt-color" for="field-f251" style="font-weight: 700;">Male</label>
                </div>
                <div class="u-input-row">
                  <input <?php if($user->getGender()=="F") echo 'checked' ?> required id="field-f9f5" type="radio" name="gender" value="F" class="u-field-input" data-calc="F">
                  <label class="u-custom-font u-field-label u-font-oswald u-text-body-alt-color" for="field-f9f5" style="font-weight: 700;">Female</label>
                </div>
              </div>
            </div>
            <div class="u-form-group u-form-name u-form-partition-factor-2 u-label-top">
              <label for="name-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-6">Recipient name</label>
              <input onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" value="<?=$user->getReceiverName()?>" required type="text" placeholder="Enter a recipient name" id="name-3b9a" name="receivername" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" >
            </div>
            <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-7">
              <label for="text-c5a4" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-7">Phone number</label>
              <input value="<?=$user->getPhoneNumber()?>" required type="text" minlength="10" maxlength="11" placeholder="Enter a phone number" id="text-c5a4" name="phonenumber" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-group u-form-textarea u-label-top u-form-group-8">
              <label for="textarea-4da5" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-8">Address</label>
              <textarea onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" required rows="4" cols="50" id="textarea-4da5" name="address" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10"  placeholder="Enter an address"><?=$user->getAddress()?></textarea>
            </div>
            <div class="u-align-center u-form-group u-form-submit u-label-top">
              <a href="#" class="u-active-palette-5-base u-black u-border-2 u-border-grey-75 u-border-hover-black u-btn u-btn-round u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-white u-radius-10 u-text-active-black u-text-hover-black u-text-white u-btn-1">Submit</a>
              <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
            </div>
          </div>
          </form>
        </div>
      </div>
    </section>
<?php require '../include/footer.php'
?>
<script>
    function checkQuote() {
        if(event.keyCode == 39 || event.keyCode == 34) {
            event.keyCode = 0;
            return false;
        }
    }
</script>
