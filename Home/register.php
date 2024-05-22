
<?php
    require '../include/init.php';
    echo"<title>REGISTER - ZEUS'S PC</title>";
    require '../include/header.php';
    $usernameError="";
    $emailError="";
    $confirmPasswordError="";
    $usernameFill="";
    $emailFill="";
    $genderSelection="";
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])){
        if($_POST["password"]!=$_POST["confirmpas"])
            $confirmPasswordError="Password doesn't match!";
        $user=new Account(null,$_POST["username"],$_POST["password"],strtolower($_POST["email"]),NULL,$_POST["gender"],null,null,0,'CUSTOMER',null);
        if($user->validateUsername()==false)
            $usernameError="Username has already been registered!";
        if($user->validateEmail()==false)
            $emailError="Email has already been taken!";
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL))
                $emailError = "Invalid email format";
        if (strlen(trim($user->getUsername())) == 0)
            $usernameError="Username can't be blank!";
        if(empty($usernameError)&&empty($emailError)&&empty($confirmPasswordError))
        {
            if($user->insert_Account()==1)
                header("location:../Home/login.php");
            else
                Method::getPopUpAnnouncement();
        }
        else
        {
            $usernameFill=$user->getUsername();
            $genderSelection=$user->getGender();
            $emailFill=$user->getEmail();
        }

    }
    
?>
<link rel="stylesheet" href="../Style/Register.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
    <section class="u-clearfix u-shading u-uploaded-video u-video u-video-cover u-section-1" id="sec-1f3f">
        <div class="u-background-video u-expanded">
            <div class="embed-responsive-1">
                <iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" class="embed-responsive-item" src="https://www.youtube.com/embed/7svCUeSQLgc?playlist=7svCUeSQLgc&loop=1&mute=1&showinfo=0&controls=0&start=1&autoplay=1&autohide=1&amp;vq=hd720" data-autoplay="1" frameborder="0" allowfullscreen=""></iframe>
            <div class="overlay"></div>
            </div>
        </div>
        <div class="u-clearfix u-sheet u-sheet-1">
            <div class="u-clearfix u-gutter-0 u-layout-wrap u-layout-wrap-1">
                <div class="u-layout">
                    <div class="u-layout-row">
                        <div class="u-align-center u-container-style u-layout-cell u-shape-rectangle u-size-60 u-layout-cell-1" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction="">
                            <div class="u-container-layout u-container-layout-1">
                                <h2 class="u-custom-font u-font-spy-agency u-text u-text-palette-3-base u-text-1">Register</h2>
                                    <div class="u-align-left u-form u-form-1">
                                        <form method="post">
                                            <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" style="text-shadow:1px 1px black">
                                                <div class="u-form-group u-form-name u-label-top">
                                                    <label for="name-5a14" class="u-custom-font u-font-spy-agency u-label u-text-white">Username (*) </label>
                                                    <span class="u-custom-font u-font-oswald field-validation-valid u-label" style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$usernameError?></span>
                                                    <input class="u-custom-font u-font-oswald u-border-2 u-border-palette-3-base u-input u-input-rectangle u-radius-11 u-white" id="name-5a14" name="username" placeholder="Enter your username" style="height:40px" type="text" value="<?=$usernameFill?>" minlength="5" required/>
                                                </div>
                                                <div class="u-form-email u-form-group u-label-top">
                                                    <label for="email-5a14" class="u-custom-font u-font-spy-agency u-label u-text-white" style="text-shadow:1px 1px black">Email (*) </label>
                                                    <span class="u-custom-font u-font-oswald field-validation-valid u-label" style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$emailError?></span>
                                                    <input class="u-custom-font u-font-oswald u-border-2 u-border-palette-3-base u-input u-input-rectangle u-radius-11 u-white"  id="email-5a14" name="email" placeholder="Example@gmail.com" style="height:40px" type="email" value="<?=$emailFill?>" required/>
                                                </div>
                                                <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-3">
                                                    <label for="text-8277" class="u-custom-font u-font-spy-agency u-label u-text-white" style="text-shadow:1px 1px black">Password (*) </label>
                                                    <br/>
                                                    <input class="u-custom-font u-font-oswald u-border-2 u-border-palette-3-base u-input u-input-rectangle u-radius-11 u-white" id="text-8277" name="password" placeholder="Enter your password" style="height:40px" type="password" value="" minlength="6" required/>
                                                </div>
                                                <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-4">
                                                    <label for="text-eefb" class="u-custom-font u-font-spy-agency u-label u-text-white" style="text-shadow:1px 1px black">Confirm Password (*) </label>
                                                    <br/>
                                                    <span class="u-custom-font u-font-oswald field-validation-valid u-label" style="color:red; font-weight:bold;text-shadow:1px 1px black"><?=$confirmPasswordError?></span>
                                                    <input class="u-custom-font u-font-oswald u-border-2 u-border-palette-3-base u-input u-input-rectangle u-radius-11 u-white" id="text-eefb" name="confirmpas" style="height:40px" type="password"  placeholder="Confirm your password" value="" required/>
                                                </div>
                                                <div class="u-form-group u-form-input-layout-horizontal u-form-radiobutton u-label-top u-form-group-5">
                                                    <label for="text-eefb" class="u-custom-font u-font-spy-agency u-label u-text-white" style="text-shadow:1px 1px black;position: relative;top: 10px;">Gender (*) </label>
                                                    <div class="u-form-radio-button-wrapper" >
                                                        <div class="u-input-row">
                                                            <input required id="Gender" name="gender" type="radio" value="M" <?php if($genderSelection=='M') echo 'checked=checked';?>/>
                                                            <p class=" u-text-white u-label-5 u-custom-font u-font-spy-agency" style="font-weight:normal;font-size:1rem;text-shadow:1px 1px black">Male</p>
                                                        </div>
                                                    <div class="u-input-row">
                                                        <input required id="Gender" name="gender" type="radio" value="F" <?php if($genderSelection=='F') echo 'checked=checked';?>/>
                                                        <p class=" u-text-white u-label-5 u-custom-font u-font-spy-agency"style="font-weight:normal;font-size:1rem;text-shadow:1px 1px black"> Female</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="u-align-left u-form-group u-form-submit u-label-top ">
                                                <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                                                <a style="text-shadow:0px 0px;background-color:gold" class="u-custom-font u-font-spy-agency u-active-black u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-black  u-radius-30 u-btn-1 ">Submit</a>
                                            </div>
                                        </div>
                                    </form>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    require '../include/footer.php'
?>
</body>


