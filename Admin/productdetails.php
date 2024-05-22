<?php 
    require '../include/init.php';
    require '../include/header_admin.php';
    if(!isset($_GET["id"]))
      header("location:../Admin/Error.php");
    else
    {
      $product=Product::getProductById($_GET["id"]);
      $listSlides=Product::getSlideImageById($_GET["id"]);
      $totalSlides=count($listSlides);
      $description=preg_replace( "~#~","\n",$product->getDescription() );
      if($product->getIdProduct()==null)
            header("location:../Admin/Error.php");
    }
?>
<link rel="stylesheet" href="../Style/ProductDetails_Admin.css" media="screen">
<section class="u-clearfix u-image u-uploaded-video u-valign-top-sm u-section-1" id="sec-d02f" data-image-width="1920" data-image-height="1080">
      <div class="u-container-style u-expanded-width u-group u-hover-feature u-shape-rectangle u-group-1" " data-animation-name="customAnimationIn" data-animation-duration="1000">
        <div class="u-container-layout u-container-layout-1">
          <div class=" u-expanded-width u-radius-10 u-shape u-shape-round u-shape-1" style="background-color:rgba(229,229,229);"></div>
          <div class="u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-none u-gallery-1">
            <?php if($totalSlides>0){?>
            <div class="u-gallery-inner u-gallery-inner-1">
              <?php if($totalSlides>=1){?>
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide" data-image-width="1440" data-image-height="900">
                  <img class="u-back-image u-expanded" src="<?=$listSlides[0]->getFileName()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-1">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
              <?php }?>
              <?php if($totalSlides>=2){?>
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide">
                  <img class="u-back-image u-expanded" src="<?=$listSlides[1]->getFileName()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-2">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
              <?php }?>
              <?php if($totalSlides>=3){?>
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide">
                  <img class="u-back-image u-expanded" src="<?=$listSlides[2]->getFileName()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-3">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
              <?php }?>
              <?php if($totalSlides>=4){?>
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide">
                  <img class="u-back-image u-expanded" src="<?=$listSlides[3]->getFileName()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-4">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
              <?php }?>
              <?php if($totalSlides==5){?>
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide">
                  <img class="u-back-image u-expanded" src="<?=$listSlides[4]->getFileName()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-5">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
              <?php }?>
            </div>
            <?php }?>
          </div>
          <div class="u-container-style u-expanded-width-xs u-group u-shape-rectangle u-group-2">
            <div class="u-container-layout u-container-layout-2">
              <img class="u-expanded-width-lg u-expanded-width-md u-expanded-width-sm u-image u-image-default u-image-1" src="../Images/Website/banner.png" alt="" data-image-width="785" data-image-height="133">
              <h4 class="u-custom-font u-font-oswald u-text u-text-body-alt-color u-text-default-lg u-text-default-md u-text-default-xl u-text-1">ID: <?=$product->getIDProduct()?></h4>
              <h4 class="u-custom-font u-font-oswald u-text u-text-body-alt-color u-text-2">NAME: <?= $product->getNameProduct()?>&nbsp;</h4>
            </div>
          </div>
          <h5 class="u-custom-font u-font-oswald u-text u-text-3">Slider images:&nbsp;</h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-4">Catalog: <span style="font-weight:500;color:red"> <?=$product->getIdCatalog(); ?></span> </h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-5">Brand: <span style="font-weight:500;color:red"> <?=$product->getIdBrand(); ?></span></h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-6">Price: <span style="font-weight:500;color:red"> <?=number_format($product->getPrice(),0,',','.'); ?></span></h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-7">Discount: <span style="font-weight:500;color:red"> <?=$product->getDiscount()*100?>%</span></h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-8">Quantity: <span style="font-weight:500;color:red"> <?=$product->getQuantity()?></span></h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-9">State: <span style="font-weight:500;color:red"> <?=$product->getState()?></span>
          <h5 class="u-custom-font u-font-oswald u-text u-text-10">Description:&nbsp;
          <div><textarea class="u-textarea" readonly="readonly" style="position:relative;left:0px;right:0pc;top:10px; resize: none;font-weight:normal;" rows="10" ><?=$description?></textarea></div></h5>
          <h5 class="u-custom-font u-font-oswald u-text u-text-default u-text-11">Product image:&nbsp;</h5>
          <div class="u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-none u-gallery-2">
            <div class="u-gallery-inner u-gallery-inner-2">
              <div class="u-effect-hover-zoom u-gallery-item">
                <div class="u-back-slide">
                  <img class="u-back-image u-expanded" src="<?=$product->getImage()?>">
                </div>
                <div class="u-over-slide u-shading u-over-slide-6">
                  <h3 class="u-gallery-heading"></h3>
                  <p class="u-gallery-text"></p>
                </div>
              </div>
            </div>
          </div>
          <a href="../Admin/changeproductinfoform.php?id=<?=$product->getIDProduct()?>" class="u-border-none u-btn u-button-style u-custom-font u-font-oswald u-grey-80 u-btn-1"><span class="u-file-icon u-icon u-text-white u-icon-1"><img src="../Images/Website/fix.png" alt=""></span>&nbsp;Fix
          </a>
        </div>
      </div>
    </section>
<?php require '../include/footer.php'?>