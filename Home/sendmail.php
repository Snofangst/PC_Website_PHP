<?php
    require '../include/init.php';
    require '../include/header.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../vendor/src/Exception.php';
    require '../vendor/src/PHPMailer.php';
    require '../vendor/src/SMTP.php';
    $nameError="";
    $messageError="";
    $nameFill=$messageFill=$emailFill="";
    $mail = new PHPMailer(true);
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])&&$_POST['email']!='zeuspc2077@gmail.com'){
      if(strlen(trim($_POST['name']))==0)
        $nameError="Name can't be blank!";
      if(strlen(trim($_POST['message']))==0)
        $messageError="Message can't be blank!";
      if($nameError==""&&$messageError=="")
      {   
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Mailer="smtp";
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_EMAIL;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_TLS;
            $mail->Port       = SMTP_PORT;
            $mail->setFrom($_POST['email'],$_POST['name']);
            $mail->addAddress(SMTP_EMAIL);
            $mail->isHTML(true);
            $mail->Subject = 'Customer Message';
            $mail->Body    ="Name:".$_POST['name']."<br> Email:".$_POST['email']."<br> Message:".$_POST['message'];
            $mail->AltBody = $_POST['message'];
            $mail->send();
            Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Message has been sent!");
        } catch (Exception $e) {
            Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
      }
      else
      {
        $nameFill=$_POST['name'];
        $messageFill=$_POST['message'];
        $emailFill=$_POST['email'];
        Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif', "Message could not be sent.<br/>".$nameError.$messageError);
      }
    }

?>
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
<link rel="stylesheet" href="../Style/Contact.css" media="screen">
<section class="u-align-center u-clearfix u-image u-section-1" id="sec-e80e" data-image-width="1655" data-image-height="931">
      <div class="u-clearfix u-sheet u-valign-bottom-sm u-valign-middle-lg u-valign-middle-md u-valign-middle-xl u-valign-middle-xs u-sheet-1">
        <div class="u-expanded-width-xs u-form u-form-1">
          <form method="post"   name="form">
          <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" style="padding: 10px">
          <img class="u-form-group u-form-image u-image u-image-contain u-image-default u-label-none u-image-1" src="../Images/Website/contact-us.png" data-image-width="2000" data-image-height="477">
            <div class="u-form-group u-form-name u-label-none">
              <label for="name-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-1">Name (*)</label>
              <input value="<?=$nameFill?>" required type="text" placeholder="Enter your Name" id="name-3b9a" name="name" class="u-white u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-email u-form-group u-label-none">
              <label for="email-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-2">Email (*)</label>
              <input value="<?=$emailFill?>" required type="email" placeholder="Enter a valid email address" id="email-3b9a" name="email" class="u-white u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10">
            </div>
            <div class="u-form-group u-form-message u-label-none">
              <label for="message-3b9a" class="u-custom-font u-font-oswald u-label u-text-body-alt-color u-label-3">Message (*)</label>
              <textarea required placeholder="Enter your message" rows="4" cols="50" id="message-3b9a" name="message" class="u-white u-custom-font u-font-oswald u-input u-input-rectangle u-radius-10"><?=$messageFill?></textarea>
            </div>
            <div class="u-align-center u-form-group u-form-submit u-label-none">
              <a href="#" class="u-active-palette-5-base u-black u-border-none u-btn u-btn-round u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-white u-radius-10 u-text-active-white u-text-hover-black u-btn-1">Submit</a>
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