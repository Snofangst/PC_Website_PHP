<title style="color:red">HOME - ZEUS'S PC</title>
<?php
    require '../include/init.php';
    require '../include/header.php';
    if(isset($_SESSION['success_purchase'])&&$_SESSION['success_purchase']==1 )
    {
        Method::getPopUpAnnouncement('#34c759','../Images/Website/purchase-successfully.gif','THANK YOU!<br/> Your submission has been sent.');
        unset($_SESSION['success_purchase']);
        unset($_SESSION['idorder']);
        unset($_SESSION['total']);
        unset($_SESSION['listitems']);
        unset($_SESSION['information']);
    }
    unset($_SESSION['information']);
    $listBestSeller=Method::getBestSellerProduct(5);
?>
<link rel="stylesheet" href="../Style/Slide.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
    <section class="u-carousel u-slide u-block-2a6e-1" id="carousel_5391" data-interval="5000" data-u-ride="carousel">
        <ol class="u-absolute-hcenter u-carousel-indicators u-block-2a6e-5">
            <li data-u-target="#carousel_5391" class="u-active u-grey-30" data-u-slide-to="0"></li>
            <li data-u-target="#carousel_5391" class="u-grey-30" data-u-slide-to="1"></li>
            <li data-u-target="#carousel_5391" class="u-grey-30" data-u-slide-to="2"></li>
        </ol>
        <div class="u-carousel-inner" role="listbox">
            <div class="u-active u-carousel-item u-clearfix u-image u-section-1-1" data-image-width="1920" data-image-height="800">
                <h2 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1000">NEXT-GEN AMD CHIP</h2>
                <p class="u-custom-font u-font-oswald u-text u-text-white u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1000"> The next-gen AMD chip has been released. Our team will&nbsp; make your dream PC come true. </p>
                <!-- <a href="#" class="u-border-2 u-border-white u-btn u-button-style u-hover-custom-color-2 u-none u-text-hover-white u-btn-1" data-animation-name="customAnimationIn" data-animation-duration="1000">chi tiết</a> -->
            </div>
            <div class="u-carousel-item u-clearfix u-image u-section-1-2" data-image-width="1920" data-image-height="800">
                <h2 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1000">INTEL CORE I9</h2>
                <p class="u-custom-font u-font-oswald u-text u-text-white u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1000"> Go live, create, and compete at the highest level with industry-leading features and the latest hybrid architecture.</p>
            </div>
            <div class="u-carousel-item u-clearfix u-image u-section-1-3" data-image-width="1920" data-image-height="800">
                <h2 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1000">
                    GEFORCE RTX 40 SERIES
                </h2>
                <p class="u-custom-font u-font-oswald u-text u-text-white u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1000">
                    NVIDIA® GEFORCE RTX™ 4090 GRAPHIC CARD&nbsp;<br>IS AVAILABLE ON ZEUS'S PC WEBSITE
                </p>
                <!-- <a href="#" class="u-border-2 u-border-white u-btn u-button-style u-hover-custom-color-2 u-none u-text-hover-white u-btn-1" data-animation-name="customAnimationIn" data-animation-duration="1000">chi tiết</a> -->
            </div>
        </div>
        <a class="u-absolute-vcenter u-carousel-control u-carousel-control-prev u-text-grey-30 u-block-2a6e-3" href="#carousel_5391" role="button" data-u-slide="prev">
            <span aria-hidden="true">
                <svg class="u-svg-link" viewBox="0 0 477.175 477.175">
                    <path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225
                    c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"></path>
                </svg>
            </span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="u-absolute-vcenter u-carousel-control u-carousel-control-next u-text-grey-30 u-block-2a6e-4" href="#carousel_5391" role="button" data-u-slide="next">
            <span aria-hidden="true">
                <svg class="u-svg-link" viewBox="0 0 477.175 477.175">
                    <path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5
                    c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"></path>
                </svg>
            </span>
            <span class="sr-only">Next</span>
        </a>
    </section>
    <?php if(!empty($listBestSeller)){?>
    <section class="u-align-center u-clearfix u-section-7" id="sec-227f" style="border: 2px solid black;">
        <div id="carousel-bd35" data-interval="0" data-u-ride="carousel" class="u-carousel u-carousel-top u-expanded-width u-slider u-slider-1">
        <ol class="u-carousel-indicators u-carousel-indicators-1">
              <?php $slide=0; 
                foreach ($listBestSeller as $item){ 
                  if($slide==0){?>
                    <li data-u-target="#carousel-bd35" class="u-active u-active-palette-3-base u-hover-palette-5-base u-shape-circle u-white" data-u-slide-to="0" style="width: 10px; height: 10px;"></li>
                  <?php } else {?>
                    <li data-u-target="#carousel-bd35" class="u-active-palette-3-base u-hover-palette-5-base u-shape-circle u-white" data-u-slide-to="<?=$slide?>" style="width: 10px; height: 10px;"></li>
                  <?php }
                  $slide++;?>
              <?php }?>
          </ol>
          <div class="u-carousel-inner" role="listbox">
          <?php $slide=0; 
                foreach ($listBestSeller as $item){ 
                  if($slide==0){?>
                    <div class="u-active u-align-center u-carousel-item u-container-style u-grey-80 u-slide u-carousel-item-1">
                      <div class="u-container-layout u-container-layout-1">
                        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
                          <div class="u-layout">
                            <div class="u-layout-row">
                              <div class="u-container-style u-image u-layout-cell u-shading u-size-60 u-image-1" style=" background-image: linear-gradient(0deg, rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url(<?=$item['FileImage']?>);" data-image-width="2048" data-image-height="1261">
                                <div class="u-container-layout u-container-layout-2">
                                  <div class="u-clearfix u-group-elements u-group-elements-1" data-animation-name="customAnimationIn" data-animation-duration="1000">
                                    <p class="u-custom-font u-font-spy-agency u-text u-text-1">Preview</p>
                                    <h2 class="u-custom-font u-font-eternal u-text u-text-2"><?=$item['nameproduct'];?><span style="font-weight: 700;"></span>
                                    </h2>
                                    <p class="u-custom-font u-font-spy-agency u-text u-text-palette-3-base u-text-3">"<?=Method::random_slogan();?>​"</p>
                                    <a href="../Home/details.php?idproduct=<?=$item['IdProduct']?>" class=" u-custom-font u-font-spy-agency u-border-2 u-border-active-palette-3-base u-border-hover-white u-border-white u-btn u-button-style u-none u-text-active-palette-3-base u-text-hover-white u-btn-1">DETAILS</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } else {?>
                    <div class="u-align-center u-carousel-item u-container-style u-grey-80 u-slide u-carousel-item-2">
                      <div class="u-container-layout u-container-layout-3">
                        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-2">
                          <div class="u-layout">
                            <div class="u-layout-row">
                              <div class="u-container-style u-image u-layout-cell u-shading u-size-60 u-image-1" style=" background-image: linear-gradient(0deg, rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url(<?=$item['FileImage']?>);" data-image-width="2048" data-image-height="1261">
                                <div class="u-container-layout u-container-layout-2">
                                  <div class="u-clearfix u-group-elements u-group-elements-1" data-animation-name="customAnimationIn" data-animation-duration="1000">
                                    <p class="u-custom-font u-font-spy-agency u-text u-text-1">Preview</p>
                                    <h2 class="u-custom-font u-font-eternal u-text u-text-2"><?=$item['nameproduct'];?><span style="font-weight: 700;"></span>
                                    </h2>
                                    <p class="u-custom-font u-font-spy-agency u-text u-text-palette-3-base u-text-3">"<?=Method::random_slogan();?>​"</p>
                                    <a href="../Home/details.php?idproduct=<?=$item['IdProduct']?>" class=" u-custom-font u-font-spy-agency u-border-2 u-border-active-palette-3-base u-border-hover-white u-border-white u-btn u-button-style u-none u-text-active-palette-3-base u-text-hover-white u-btn-1">DETAILS</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php }
                  $slide++;?>
              <?php }?>
          </div>
        </div>
      </section>
    <?php } ?>
    <section class="u-align-center u-clearfix u-container-align-center u-grey-90 u-section-3" id="carousel_72c9">
        <div class="u-clearfix u-sheet u-sheet-1">
            <div class="u-expanded-width u-list u-list-1">
                <div class="u-repeater u-repeater-1">
                    <div class="u-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle">
                        <div class="u-container-layout u-similar-container u-valign-top-md u-valign-top-sm u-container-layout-1">
                            <img alt="" class="u-border-5 u-border-palette-4-base u-hover-feature u-image u-image-round u-image-1" data-image-width="730" data-image-height="487" src="../Images/Website/Best-Online-Games-for-PC-1.png" data-animation-name="customAnimationIn" data-animation-duration="1000">
                            <h4 class="u-text u-text-1 u-custom-font u-font-spy-agency" data-animation-name="customAnimationIn" data-animation-duration="1000">Our story</h4>
                            <p class="u-custom-font u-font-oswald u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1000">We have a mission to help Vietnamese gamers build their dream PCs and bring a new wave to the country's gaming platform. We are passionate about PC and want to help Vietnamese gamers feel the best with each PC we bring.</p>
                        </div>
                    </div>
                    <div class="u-align-center u-container-align-left u-container-style u-list-item u-repeater-item u-shape-rectangle">
                        <div class="u-container-layout u-similar-container u-valign-top-md u-valign-top-sm u-container-layout-2">
                            <img alt="" class="u-border-5 u-border-palette-4-base u-hover-feature u-image u-image-round u-image-2" data-image-width="750" data-image-height="500" src="../Images/Website/637789173642585147_untitled-2.png" data-animation-name="customAnimationIn" data-animation-duration="1000">
                            <h4 class="u-text u-text-3 u-custom-font u-font-spy-agency" data-animation-name="customAnimationIn" data-animation-duration="1000">Our PCs</h4>
                            <p class="u-custom-font u-font-oswald u-text u-text-4" data-animation-name="customAnimationIn" data-animation-duration="1000">Our PCs can meet your gaming, work, entertainment, and graphic design needs with the latest and trending components for the smoothest and most realistic experience!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="u-align-center u-clearfix u-white u-section-4" id="sec-cf53">
        <div class="u-clearfix u-sheet u-sheet-1">
            <h2 class="u-custom-font u-font-courier-new u-text u-text-1">PURCHASING POLICY AT ZEUS'S PC</h2>
            <div class="u-expanded-width u-layout-grid u-list u-list-1">
                <div class="u-repeater u-repeater-1">
                    <div class="u-container-style u-list-item u-repeater-item u-list-item-1">
                        <div class="u-container-layout u-similar-container u-container-layout-1">
                            <img alt="" class="u-image u-image-contain u-image-round u-radius-10 u-image-1" data-image-width="576" data-image-height="512" src="../Images/Website/money-bill-1-wave-solid.svg">
                            <p class="u-align-center u-custom-font u-font-courier-new u-text u-text-3">
                                PAYMENT METHOD<br>
                            </p>
                            <h5 class="u-align-center u-custom-font u-font-courier-new u-text u-text-4">PLAY THEN PAY</h5>
                            <ul class="u-align-left u-custom-font u-font-courier-new u-text u-text-5">
                                <li>Get instant deals and low prices</li>
                                <li>Late payment without penalty</li>
                                <li>Up to 36 months in installments</li>
                                <li>Pay when the packages arrive</li>
                            </ul>
                        </div>
                    </div>
                    <div class="u-container-style u-list-item u-repeater-item u-list-item-2">
                        <div class="u-container-layout u-similar-container u-container-layout-2">
                            <img alt="" class="u-image u-image-round u-radius-10 u-image-2" data-image-width="640" data-image-height="512" src="../Images/Website/handshake-solid.svg">
                            <p class="u-align-center u-custom-font u-font-courier-new u-text u-text-6">SHIPPING</p>
                            <h5 class="u-align-center u-custom-font u-font-courier-new u-text u-text-7">FAST &amp; RELIABLE</h5>
                            <ul class="u-align-left u-custom-font u-font-courier-new u-text u-text-8">
                                <li>Nationwide shipping</li>
                                <li>Can be returned within 1 week of receipt</li>
                                <li>Check the your packages upon arrival</li>
                                <li>Freeship within Ho Chi Minh city</li>
                            </ul>
                        </div>
                    </div>
                    <div class="u-container-style u-list-item u-repeater-item u-list-item-3">
                        <div class="u-container-layout u-similar-container u-container-layout-3">
                            <img alt="" class="u-image u-image-contain u-image-round u-radius-10 u-image-3" data-image-width="512" data-image-height="512" src="../Images/Website/headset-solid.svg">
                            <p class="u-align-center u-custom-font u-font-courier-new u-text u-text-9">SUPPORT TEAM</p>
                            <h5 class="u-align-center u-custom-font u-font-courier-new u-text u-text-10">ASSIST 24/7&nbsp;</h5>
                            <ul class="u-align-left u-custom-font u-font-courier-new u-text u-text-11">
                                <li>There are always staffs on duty</li>
                                <li>Free calls</li>
                                <li>Enthusiastic and friendly support</li>
                                <li>Fix computer problems at your house</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="u-clearfix u-section-5" id="carousel_f18b" style="background-color:gold">
        <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-sheet-1">
            <div class="u-clearfix u-expanded-width-lg u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-gutter-0 u-layout-custom-sm u-layout-custom-xs u-layout-wrap u-layout-wrap-1">
                <div class="u-gutter-0 u-layout">
                    <div class="u-layout-row">
                        <div class="u-container-style u-layout-cell u-size-10 u-image-5">
                            <div class="u-container-layout u-valign-middle-sm u-valign-top-md u-valign-top-xs u-container-layout-1">
                                <img class="u-image u-image-contain u-image-default u-image-1" src="../Images/Website/kindpng_14195301.png" alt="" data-image-width="509" data-image-height="166">
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-10 u-layout-cell-2">
                            <div class="u-container-layout u-valign-top-md u-container-layout-2">
                                <img src="../Images/Website/pngwing.com.png" alt="" class="u-image u-image-default u-image-2" data-image-width="3508" data-image-height="2480">
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-10 u-layout-cell-3">
                            <div class="u-container-layout u-valign-middle-lg u-valign-middle-xl u-container-layout-3">
                                <img src="../Images/Website/lo73l560-logitech-logo-logitech-g-logo-logok-in-2021-monogram-logo-logo-logitech.png" alt="" class="u-image u-image-default u-image-3" data-image-width="1000" data-image-height="750">
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-10 u-layout-cell-4">
                            <div class="u-container-layout u-valign-middle-xl u-container-layout-4">
                                <img class="u-expanded-width-lg u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-image u-image-contain u-image-default u-image-4" src="../Images/Website/pngwing.com1.png" alt="" data-image-width="2500" data-image-height="1000">
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-10 u-layout-cell-5">
                            <div class="u-container-layout u-valign-middle-lg u-valign-middle-xl u-container-layout-5">
                                <img class="u-expanded-width-xs u-image u-image-contain u-image-default u-image-5" src="../Images/Website/Intel-2020-New.png" alt="" data-image-width="4478" data-image-height="3358">
                            </div>
                        </div>
                        <div class="u-container-style u-layout-cell u-size-10 u-layout-cell-6">
                            <div class="u-container-layout u-valign-middle-lg u-valign-middle-sm u-valign-middle-xl u-valign-middle-xs u-container-layout-6">
                                <img src="../Images/Website/Nvidia-Vertical-Black-Logo.wine.png" alt="" class="u-image u-image-default u-image-6" data-image-width="3000" data-image-height="2000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
    require '../include/footer.php';
?>
</body>
