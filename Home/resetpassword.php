<title style="color:red">HOME - ZEUS'S PC</title>
<?php
    require '../include/init.php';
    require '../include/header.php';
    $ConfirmpassError=null;
    if(isset($_GET['token'],$_GET['id']))
    {
      $acc=new Account();
      $acc=$acc->checkValidToken($_GET['id'],$_GET['token']);
      if(!empty($acc))
      {
        if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"]))
        {
          if(!empty($acc->checkValidToken($_GET['id'],$_GET['token'])))
          {
            if($_POST['password']!=$_POST['confirmpass'])
              $ConfirmpassError="Password doesn't macth!";
            else
            {
              if($acc->updatePass($_POST['password'],$_GET['id'])==1)
                header("location:../Home/login.php");
              else
                Method::getPopUpAnnouncement();
            }
          }
          else
            header("location: ../Home/Error.php");
        }
      }
      else
        header("location: ../Home/Error.php");
    }
    else
      header("location:../Home/Error.php");
?>
<link rel="stylesheet" href="../Style/ResetPassword.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
<section class="u-clearfix u-image u-section-1" id="sec-898d" data-image-width="1920" data-image-height="1006">
      <div class="u-clearfix u-sheet u-sheet-1">
        <img class="u-expanded-width-sm u-expanded-width-xs u-image u-image-contain u-image-default u-image-1" src="../Images/Website/Untitled.png" alt="" data-image-width="1178" data-image-height="408">
        <div class="u-expanded-width-xs u-form u-form-1">
          <form method="post"  style="padding: 15px;">
            <div class="u-clearfix u-form-spacing-15 u-form-vertical u-inner-form">
                <div class="u-form-group u-form-name u-label-none">
                  <label for="name-ef64" class="u-label">Name</label>
                  <input minlength="6" required type="password" placeholder="Enter your new password" id="name-ef64" name="password" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="">
                </div>
                <div class="u-form-email u-form-group u-label-none">
                  <label for="email-ef64" class="u-label">Email</label>
                  <input required type="password" placeholder="Re-enter your password to verify" id="email-ef64" name="confirmpass" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="">
                </div>
                <div class="u-align-center u-form-group u-form-submit">
                  <a href="#" class="u-active-white u-border-2 u-border-grey-75 u-btn u-btn-round u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-black u-radius-10 u-text-active-black u-text-hover-white u-white u-btn-1">Submit</a>
                  <input  minlength="6" type="submit" value="submit" name="submit" class="u-form-control-hidden">
                </div>
                <span class="field-validation-valid text-danger u-custom-font u-font-oswald" style="color:red;text-shadow:1px 1px black;font-weight:bold"><?= $ConfirmpassError?></span>
            </div>
          </form>
        </div>
      </div>
    </section>
<?php
    require '../include/footer.php';
?>