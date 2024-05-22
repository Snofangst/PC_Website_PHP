<?php
    if(!isset($_SESSION['login']))
        header("location: ../Home/Error.php");
    else
        $login=$_SESSION['login'];
?>
<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="description" content="">
        <title>ZEUS'S PC</title>
        <link rel="icon" href="../Images/Website/tablogo.png">
        <link rel="stylesheet" href="../Style/nicepage.css" media="screen">
        <script class="u-script" type="text/javascript" src="../Scripts/jquery.js" defer=""></script>
        <script class="u-script" type="text/javascript" src="../Scripts/nicepage.js" defer=""></script>
        <script class="u-script" type="text/javascript" src="../Scripts/javascript.js" defer=""></script>
        <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
        <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bangers:400">
        <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Magra:400,700">
        <meta name="theme-color" content="#478ac9">
        <meta property="og:title" content="Page 1">
        <meta property="og:type" content="website">
    </head>
    <body data-home-page="Page-1.html" data-home-page-title="Page 1" class="u-body u-xl-mode" data-lang="en">
        <header style="border:3px solid #333333;border-left:none;border-right:none;border-top:none"class=" u-border-no-bottom u-border-no-left u-border-no-right u-border-no-top u-header u-section-row-container"  id="sec-3ff4" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction="">
            <div class="u-section-rows">
                <div class=" u-section-row u-section-row-1" style="background-image:url(../Images/Website/ismail-inceoglu-on-fire.jpg)" id="sec-d6d6">
                    <div class="u-clearfix u-sheet u-valign-top-xs u-sheet-1">
                        <a href="../Home/index.php" class="u-image u-logo u-image-1" data-image-width="317" data-image-height="354">
                            <img src="../Images/Website/Zeus.png" class="u-logo-image u-logo-image-1">
                        </a>
                        <nav class="u-menu u-menu-dropdown u-menu-open-right u-offcanvas u-menu-1">
                            <div class="menu-collapse" style="font-size: 1rem; letter-spacing: 0px; font-weight: 700; text-transform: uppercase;">
                                <a class="u-button-style u-custom-active-border-color u-custom-active-color u-custom-border u-custom-border-color u-custom-borders u-custom-hover-border-color u-custom-hover-color u-custom-left-right-menu-spacing u-custom-text-active-color u-custom-text-color u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-nav-link" href="#">
                                    <svg class="u-svg-link" viewBox="0 0 24 24">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-f7ab"></use>
                                    </svg>
                                    <svg class="u-svg-content" version="1.1" id="svg-f7ab" viewBox="0 0 16 16" x="0px" y="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <rect y="1" width="16" height="2"></rect>
                                            <rect y="7" width="16" height="2"></rect>
                                            <rect y="13" width="16" height="2"></rect>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="u-custom-menu u-nav-container u-custom-font u-font-oswald">
                                <ul class="u-nav u-spacing-30 u-unstyled u-nav-1">
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" href="../Admin/index.php" style="padding: 10px 0px;">HOME</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" href="../Admin/about.php" style="padding: 10px 0px;">ABOUT</a>
                                    </li>
                                    <li class="u-nav-item">
                                    <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white " style="padding: 10px 7px 10px 0px;">MANAGEMENT</a>
                                        <div class="u-nav-popup">
                                            <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10">
                                                <li class="u-nav-item">
                                                    <a href="../Admin/usermanagement.php" class="u-button-style u-nav-link u-white">USER</a>
                                                </li>
                                                <li class="u-nav-item">
                                                    <a href="../Admin/productmanagement.php" class="u-button-style u-nav-link u-white">PRODUCT</a>
                                                </li>
                                                <li class="u-nav-item">
                                                    <a href="../Admin/ordermanagement.php" class="u-button-style u-nav-link u-white">ORDER</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <?php if( isset($_SESSION['login']) && $login[2]=="ADMIN"): ?>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white " href="../Home/logout.php" style="padding: 10px 7px 10px 0px;">LOGOUT</a>
                                    </li>
                                    <?php endif; ?>
                                    
                                </ul>
                            </div>
                            <div class="u-custom-menu u-nav-container-collapse u-custom-font u-font-oswald">
                                <div class="u-container-style u-inner-container-layout u-opacity u-opacity-100 u-palette-3-base u-sidenav">
                                    <div class="u-inner-container-layout u-sidenav-overflow">
                                        <div class="u-menu-close"></div>
                                        <ul style="text-align:right;margin-left:110px;font-weight:bold;" class="u-nav u-popupmenu-items u-text-active-black u-text-hover-grey-50 u-unstyled u-nav-4">
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Admin/index.php">HOME</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Admin/about.php">ABOUT</a>
                                            </li>
                                            <?php if(isset($_SESSION['login']) && $login[2]=="ADMIN"): ?>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px"> MANAGEMENT</a>
                                                <div class="u-nav-popup">
                                                    <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10">
                                                        <li class="u-nav-item">
                                                            <a href="../Admin/usermanagement.php"style="margin-bottom:10px; text-align:right" class="u-button-style u-nav-link">USER</a>
                                                        </li>
                                                        <li class="u-nav-item">
                                                            <a href="../Admin/productmanagement.php"style="margin-bottom:10px; text-align:right" class="u-button-style u-nav-link">PRODUCT</a>
                                                        </li>
                                                        <li class="u-nav-item">
                                                            <a href="../Admin/ordermanagement.php"style="margin-bottom:10px; text-align:right" class="u-button-style u-nav-link">ORDER</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/logout.php">LOGOUT</a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        
        