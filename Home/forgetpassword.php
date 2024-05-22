<title style="color:red">HOME - ZEUS'S PC</title>
<?php
    $EmailError=$UsernameError=null;
    $EmailFill=$UsernameFill=null;
    require '../include/init.php';
    require '../include/header.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../vendor/src/Exception.php';
    require '../vendor/src/PHPMailer.php';
    require '../vendor/src/SMTP.php';
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"]))
    {
        if(strlen(trim($_POST['username']))==0)
            $UsernameError="Username can't be blank!";
        else
        {
            $acc=new Account();
            $acc->setIdAcc($acc->checkUsernameAndEmail($_POST['username'],$_POST['email']));
            if(empty($acc->getIdAcc()))
                $UsernameError="Invalid Account!";
            else
            {
                $acc->setToken(Method::getRandomPassword(50));
                $acc->setExpiredDate(date('Y-m-d H:i:s', strtotime('1 hour')));
                $baseurl="http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
                if($acc->updateToken()==1)
                {
                    try {
                        //Server settings
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Mailer="smtp";
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = SMTP_EMAIL;
                        $mail->Password   = SMTP_PASS;
                        $mail->SMTPSecure = SMTP_TLS;
                        $mail->Port       = SMTP_PORT;
                        $mail->setFrom(SMTP_EMAIL);
                        $mail->addAddress($_POST['email'],$_POST['username']);
                        $mail->isHTML(true);
                        $mail->Subject = '!PASSWORD RESET!';
                        $mail->Body    ="Email:".$_POST['email']."<br> Password reset link: ".$baseurl."/resetpassword.php?id=".$acc->getIdAcc()."&token=".$acc->getToken()."<br> Message: Please don't share this link to anybody else.<br/> <p style="."color:red".">Notice: You have 1 hour to change your password</p>";
                        $mail->AltBody = "Email:".$_POST['email']." Password reset link: ".$baseurl."/resetpassword.php?id=".$acc->getIdAcc()."&token=".$acc->getToken()." Message: Please don't share this link to anybody else! Notice: You have 1 hour to change your password";
                        $mail->send();
                        Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif','SUCCESS!<br/> Please check your email inbox!');
                    } catch (Exception $e) {
                        Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
                    }
                    
                }
                else
                {
                    Method::getPopUpAnnouncement();
                }

            }
        }
        
    }
?>
<link rel="stylesheet" href="../Style/ForgetPassword.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
<section class="u-align-center u-clearfix u-image u-section-1" id="sec-e80e" data-image-width="1920" data-image-height="1006">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-form u-form-1">
          <form method="post" style="padding: 10px" source="email" name="form">
          <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form">
            <img class="u-form-group u-form-image u-image u-image-contain u-image-default u-label-none u-image-1" src="../Images/Website/forgot-password.png" data-image-width="2000" data-image-height="477">
                <div class="u-form-group u-form-name u-label-none">
                    <label for="name-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-1">Name (*)</label>
                    <input required type="text" placeholder="Enter your username" id="name-3b9a" name="username" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="">
                </div>
                <div class="u-form-email u-form-group u-label-none">
                    <label for="email-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-2">Email (*)</label>
                    <input required type="email" placeholder="Enter a valid email address" id="email-3b9a" name="email" class="u-white u-border-2 u-border-black u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10" required="">
                </div>
                <?php if($UsernameError!=NULL){?>
                    <ul class="u-form-group" style="margin-left:20px;margin-top:-5px">
                        <li class="field-validation-valid text-danger u-custom-font u-font-oswald" style="color:red;text-shadow:1px 1px black;font-weight:bold">
                            <?=$UsernameError?>
                        </li>
                    </ul>
                <?php } ?>
                <div class="u-align-center u-form-group u-form-submit u-label-none">
                    <a href="#" class="u-active-palette-5-base u-black u-border-2 u-border-grey-75 u-border-hover-black u-btn u-btn-round u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-white u-radius-10 u-text-active-black u-text-hover-black u-text-white u-btn-1">Submit</a>
                    <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                </div>
             
          </div>
          </form>
        </div>
      </div>
    </section>
<?php
    require '../include/footer.php';
?>