<title>LOGIN - ZEUS'S PC</title>
<?php
    require '../include/init.php';
    require '../include/header.php';

    $loginError="";
    $usernameFill="";
    $usernameValidationResult="";
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])){
        $usernameFill=$_POST["username"];
        $user=new Account(null,$_POST["username"],$_POST["password"],null,null,null,null,null,0,null,null);
        if($user->validateUsername()==false||Account::customValidationRow('email',strtolower($user->getUsername()))==false)
        {
            if($user->validatePassword()==true)
            {
                if($user->validateBannedUser()==false)
                {
                    $typeacc=Account::getUserDataInDatabase($user->getUsername(),'TypeAcc');
                    $idacc=Account::getUserDataInDatabase($user->getUsername(),'IdAcc');
                    $name=Account::getUserDataInDatabase($user->getUsername(),'Name');
                    unset($_SESSION['login']);
                    $_SESSION['login']=array($idacc['IdAcc'],$name['Name'], $typeacc['TypeAcc'],);
                    if($typeacc['TypeAcc']=="ADMIN")
                        header("location: ../Admin/index.php");
                    else
                        header("location:../Home/index.php");
                }
                else
                    $loginError="Warning: This user has been banned!";
            }
            else
                $loginError="Warning: Incorrect (username/email) or password!";
        }
        else
            $loginError="Warning: Incorrect (username/email) or password!";
    }
    
?>
<link rel="stylesheet" href="../Style/Login_Form.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
    <section class="u-clearfix u-uploaded-video u-video u-section-1" id="sec-9413">
        <div class="u-background-video u-expanded">
            <div class="embed-responsive-1">
            <iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" class="embed-responsive-item" src="https://www.youtube.com/embed/yHZOV6avCAg?playlist=yHZOV6avCAg&loop=1&mute=1&showinfo=0&controls=0&start=1&autoplay=1&autohide=1" data-autoplay="1" frameborder="0" allowfullscreen=""></iframe>
                <div class="overlay"></div>
            </div>
        </div>
            <div class="u-clearfix u-sheet u-sheet-1">
                <div class="u-align-center  u-container-style u-group   u-group-1">
                    <div class="u-container-layout u-container-layout-1">
                        <h2 class="u-custom-font u-font-spy-agency u-text-1" style="text-shadow:1px 1px black;color:orange">Login</h2>
                        <div class="u-expanded-width u-form u-login-control u-form-1" style="height:1000px">
                            <form method="post">
                                <div class="u-clearfix u-form-custom-backend u-form-spacing-22 u-form-vertical u-inner-form" source="custom" name="form-2" style="padding: 10px">
                                    <div class="u-form-group u-form-name">
                                        <label  for="username-708d" class="u-custom-font u-font-spy-agency u-form-control u-text-white" style="text-shadow:1px 1px black">Username (*) </label>
                                        <input class="u-custom-font u-font-oswald u-grey-5 u-input u-radius-11" id="username-708d" name="username" placeholder="Enter your Username" style="opacity:90%;border:3px solid orange" type="text" value="<?=$usernameFill?>" required/>
                                    </div>
                                    <div class="u-form-group u-form-password">
                                        <label for="password-708d" class="u-custom-font u-font-spy-agency u-form-control u-text-white " style="text-shadow:1px 1px black">Password (*) </label>
                                        <input class="u-custom-font u-font-oswald u-grey-5 u-input u-radius-11" id="password-708d" name="password" placeholder="Enter your Password" style="opacity:90%;border:3px solid orange" type="password" value="" required/>
                                    </div>
                                        <div class="u-form-checkbox u-form-group">
                                        <span class="field-validation-valid text-danger u-custom-font u-font-oswald" style="color:red;text-shadow:1px 1px black;font-weight:bold"><?= $loginError?></span>
                                    </div>
                                    <div class="u-align-center u-form-group u-form-submit">
                                        <a href="#" class="u-custom-font u-font-spy-agency u-border-none u-btn u-btn-round u-btn-submit u-button-style u-radius-17 u-btn-1" style="background-color:orange;font-weight:bold;color:black">Submit</a>
                                        <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="../Home/forgetpassword.php" class="u-custom-font u-font-oswald u-border-1 u-border-active-palette-5-dark-3 u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-login-control u-login-forgot-password u-none u-radius-0 u-text-white u-top-left-radius-0 u-top-right-radius-0 u-btn-2">Forgot password?</a>
                        <a href="../Home/register.php" class="u-custom-font u-font-oswald u-border-1 u-border-active-black u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-btn u-button-style u-login-control u-login-create-account u-none u-text-white u-btn-3" style="text-shadow:1px 1px black">Don't have an account?</a>
                    </div>
                </div>
            </div>
    </section>
<?php
    require '../include/footer.php' 
?>
</body>
