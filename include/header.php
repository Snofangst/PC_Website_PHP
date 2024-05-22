<?php

    if(isset($_SESSION['login'])){
        $login=$_SESSION['login'];
    }
    $color='gold';
    if(isset($_GET['catalog']))
    {
        switch(strtolower($_GET['catalog']))
        {
            case 'pc':
                 $color='#c1e903';
                 break;
            case 'mouse':
                $color='#ff4696';
                break;
            case 'monitor':
                $color='#ff002f';
                break;
            case 'keyboard':
                $color='#00b8ff';
                break;
        }
    }
    $_SESSION['color']=$color;
?>
<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../Images/Website/tablogo.png">
        <meta charset="utf-8">
        <meta name="description" content="">
        <title>ZEUS'S PC</title>
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
    <body class="u-body u-xl-mode" data-lang="en">
        <header  class=" u-border-no-bottom u-border-no-left u-border-no-right u-border-no-top u-header u-sticky u-section-row-container" id="sec-3ff4" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction="">
            <div class="u-section-rows">
                <div class="u-grey-80 u-section-row u-section-row-1" id="sec-d6d6">
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
                            <div style="font-weight:100" class="u-custom-menu u-nav-container u-custom-font u-font-oswald">
                                <ul class="u-nav u-spacing-30 u-unstyled u-nav-1">
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" href="../Home/index.php" style="padding: 10px 0px;">HOME</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" href="../Home/about.php" style="padding: 10px 0px;">ABOUT</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15" <?php if(strpos($_SERVER['REQUEST_URI'],"product.php")) echo 'style="color:#f1c50e;border-color:#f1c50e;padding: 10px 0px;"'; else echo 'style="color:white;padding: 10px 0px;"' ?>  href="../Home/product.php" style="">PRODUCT</a>
                                    </li>
                                    <?php if(!isset($_SESSION['login'])):?>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" style="padding: 10px 0px;" href="../Home/login.php">LOGIN</a>
                                    </li>
                                    
                                    <?php endif;?>
                                   
                                    <?php if( isset($_SESSION['login']) && $login[2]=="ADMIN"): ?>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" style="padding: 10px 0px;">ADMIN</a>
                                        <div class="u-nav-popup">
                                            <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10">
                                                <li class="u-nav-item">
                                                    <a href="../Admin/index.php" class="u-button-style u-nav-link u-white"><?=$login[1]?></a>
                                                </li>
                                                <li class="u-nav-item">
                                                    <a class="u-button-style u-nav-link u-white" href="../Home/logout.php">LOGOUT</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <?php endif; ?>
                                    <?php if(isset($_SESSION['login']) &&$login[2]=="CUSTOMER"): ?>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white" style="padding: 10px 0px;">ACCOUNT</a>
                                        <div class="u-nav-popup">
                                            <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10">
                                                <li class="u-nav-item">
                                                    <a href="../Users/userdetails.php" class="u-button-style u-nav-link u-white"><?=$login[1]?></a>
                                                </li>
                                                <li class="u-nav-item">
                                                    <a class="u-button-style u-nav-link u-white" href="../Home/logout.php">LOGOUT</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <?php endif; ?>
                                    <?php if(!isset($_SESSION['login']) || $login[2]!="ADMIN"):?>
                                    <li class="u-nav-item">
                                        <a class="u-border-2 u-border-active-palette-3-base u-border-hover-grey-25 u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-base u-text-hover-grey-15 u-text-white " href="../Users/cart.php" style="padding: 10px 7px 10px 0px;">CART</a>
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
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/index.php">HOME</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/about.php">ABOUT</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/product.php">PRODUCT</a>
                                            </li>
                                            <?php if(!isset($_SESSION['login'])):?>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/login.php">LOGIN</a>
                                            </li>
                                           
                                            <?php endif;?>
                                            <?php if(isset($_SESSION['login']) && $login[2]=="ADMIN"): ?>
                                            
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px"> ADMIN</a>
                                                <div class="u-nav-popup">
                                                    <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10">
                                                        <li class="u-nav-item">
                                                            <a href="../Admin/index.php"style="margin-bottom:10px" class="u-button-style u-nav-link"><?=$login[1]?></a>
                                                        </li>
                                                        <li class="u-nav-item">
                                                            <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/logout.php">LOGOUT</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endif; ?>
                                            <?php if( isset($_SESSION['login']) &&$login[2]=="CUSTOMER"): ?>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" style="margin-bottom:10px">ACCOUNT</a>
                                                <div class="u-nav-popup" >
                                                    <ul class="u-h-spacing-20 u-nav u-unstyled u-v-spacing-10" style="text-align:right">
                                                        <li class="u-nav-item">
                                                            <a href="../Users/userdetails.php" class="u-button-style u-nav-link"><?=$login[1]?></a>
                                                        </li>
                                                        <li class="u-nav-item">
                                                            <a class="u-button-style u-nav-link" style="margin-bottom:10px" href="../Home/logout.php">LOGOUT</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php endif; ?>
                                            <?php if(!isset($login)||$login[2]!="ADMIN"):?>
                                            <li class="u-nav-item">
                                                <a class="u-button-style u-nav-link" href="../Users/cart.php">CART</a>
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
                <div style ="background-color:<?=$color?>" class=" u-section-row u-section-row-2" data-animation-name="" data-animation-duration="0" data-animation-delay="0" data-animation-direction="" id="sec-378a">
                    <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xs u-sheet-2">
                        <nav class="u-menu u-menu-one-level u-offcanvas u-menu-2" data-position="" data-responsive-from="MD">
                            <div class="menu-collapse" style="font-size: 1.25rem; letter-spacing: 0px; font-weight: 700;">
                                <a class="u-button-style u-custom-active-border-color u-custom-border u-custom-border-color u-custom-borders u-custom-hover-border-color u-custom-left-right-menu-spacing u-custom-padding-bottom u-custom-text-active-color u-custom-text-color u-custom-text-decoration u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-file-icon u-nav-link u-file-icon-1" href="#">
                                    <img style="height:100%;width:auto" src="../Images/Website/5703931.png" alt="">
                                </a>
                            </div>
                            <div class="u-custom-menu u-nav-container  u-custom-font u-font-oswald">
                                <ul class="u-nav u-unstyled u-nav-7">
                                    <li class="u-nav-item">
                                        <a <?php if(strpos($_SERVER['REQUEST_URI'],"catalog=PC")) echo 'style="color:white;border-color:black;padding: 6px 36px;"'; else echo 'style="border-color:black;padding: 6px 36px;"' ?> class="u-border-1 u-border-no-left u-border-no-bottom u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-1-base"  href="../Home/product.php?catalog=PC" >PC</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a <?php if(strpos($_SERVER['REQUEST_URI'],"catalog=Mouse")) echo 'style="color:white;border-color:black;padding: 6px 36px;"'; else echo 'style="border-color:black;padding: 6px 36px;"' ?> class="u-border-1 u-border-no-left u-border-no-bottom  u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-1-base" href="../Home/product.php?catalog=Mouse">MOUSE</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a <?php if(strpos($_SERVER['REQUEST_URI'],"catalog=Monitor")) echo 'style="color:white;border-color:black;padding: 6px 36px;"'; else echo 'style="border-color:black;padding: 6px 36px;"' ?> class="u-border-1 u-border-no-left u-border-no-bottom  u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-1-base" href="../Home/product.php?catalog=Monitor">MONITOR</a>
                                    </li>
                                    <li class="u-nav-item">
                                        <a <?php if(strpos($_SERVER['REQUEST_URI'],"catalog=Keyboard")) echo 'style="color:white;border-color:black;padding: 6px 36px;"'; else echo 'style="border-color:black;padding: 6px 36px;"' ?> class="u-border-1 u-border-no-left  u-border-no-bottom  u-border-no-top u-button-style u-nav-link u-text-active-palette-1-base u-text-hover-palette-1-base" href="../Home/product.php?catalog=Keyboard">KEYBOARD</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="u-custom-menu u-nav-container-collapse  u-custom-font u-font-oswald">
                                <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav" style="margin-left:0;margin-right:auto">
                                    <div class="u-inner-container-layout u-sidenav-overflow">
                                        <div class="u-menu-close"></div>
                                        <ul style="font-weight:bold;" class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-8">
                                            <li class="u-nav-item">
                                                <a style="padding-bottom:10px" class="u-button-style u-nav-link"  href="../Home/product.php?catalog=PC">PC</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a style="padding-bottom:10px" class="u-button-style u-nav-link"  href="../Home/product.php?catalog=Mouse">MOUSE</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a style="padding-bottom:10px" class="u-button-style u-nav-link" href="../Home/product.php?catalog=Monitor">MONITOR</a>
                                            </li>
                                            <li class="u-nav-item">
                                                <a style="padding-bottom:10px" href="../Home/product.php?catalog=Keybroad" class="u-button-style u-nav-link">KEYBOARD</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
                            </div>
                        </nav>
                        <?php if(!isset($_GET['catalog'])){ ?>
                            <form action="../Home/product.php" method="get" class=" u-border-1 u-border-grey-30 u-search u-search-left u-white u-search-1">
                                    <button class="u-search-button" type="submit">
                                        <span class="u-search-icon u-spacing-10">
                                            <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 56.966 56.966">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-469b"></use>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="svg-469b" x="0px" y="0px" viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" class="u-svg-content">
                                                <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                <input class="u-custom-font u-font-oswald u-search-input" , type="search" , name="search" , placeholder="Search" , value="">
                            </form>
                        <?php } else {?>
                            <form action="../Home/product.php" method="get"  class="u-custom-font u-font-agency u-border-1 u-border-grey-30 u-search u-search-left u-white u-search-1">
                                    <button class="u-search-button" type="submit">
                                        <span class="u-search-icon u-spacing-10">
                                            <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 56.966 56.966">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-469b"></use>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="svg-469b" x="0px" y="0px" viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" class="u-svg-content">
                                                <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                    <input class="u-custom-font u-font-oswald u-search-input" , type="search" , name="search" , placeholder="Search" , value="">
                                    <input class="u-custom-font u-font-oswald u-search-input" , name="catalog" , value="<?=$_GET['catalog']?>" style="display: none;">
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </header>
        
        