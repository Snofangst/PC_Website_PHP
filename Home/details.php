
<?php
    require '../include/init.php';
    if(!isset($_GET["idproduct"]))
      header("location:../Home/product.php");
    else
    {
      $product=Product::getProductById($_GET["idproduct"]);
      // if($product==null||$product->getState()=='HIDDEN')
      //   header("location:../Home/Error.php");
      $listSlides=Product::getSlideImageById($_GET["idproduct"]);
      $totalSlides=count($listSlides);
      $descriptionlist=explode('#',$product->getDescription());
      
    }
    echo"<title>".$product->getNameProduct()." - ZEUS'S PC</title>";
    require "../include/header.php";

    if(isset($_SESSION['login']))
      $idacc=$_SESSION['login'][0];
    if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])&&isset($idacc)){
      $orderid=Database::getIdOrderInProgressByAccId($idacc);
      if(empty($orderid)){
        $order=new Orders();
        $order->setIdAcc($idacc);
        $order->insertNewOrder();
        $orderid=Database::getIdOrderInProgressByAccId($idacc);
      }
      $orderdetails=new OrderDetails($orderid,$_GET["idproduct"],$_POST['quantity'],0,0);
      $success=$orderdetails->insertItem();
    }
    else if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])&&!isset($idacc)){
      if(!isset($_COOKIE['cart']))
      {
        $cart = array();
        $orderdetails=new OrderDetails(null,$_GET["idproduct"],$_POST['quantity']);
        array_push($cart,$orderdetails);
        setcookie('cart', serialize($cart), time() + (86400 * 30),"/");
        $success=1;
      }
      else
      {
        $cart= unserialize($_COOKIE['cart'], ["allowed_classes" => true]);
        $flag=0;
        foreach($cart as $item)
        {
          if($item->getIdProduct()==$_GET["idproduct"])
          {
            $max=$product->getQuantity();
            if($item->getQuantity()+$_POST['quantity']>$max)
              $item->setQuantity($max);
            else
              $item->setQuantity($item->getQuantity()+$_POST['quantity']);
            $flag=1;
            break;
          }
        }
        if($flag==0)
        {
          $orderdetails=new OrderDetails(null,$_GET["idproduct"],$_POST['quantity'],0,0);
          array_push($cart,$orderdetails);
        }
        
        setcookie('cart', serialize($cart), time() + (86400 * 30),"/");
        $success=1;
      }
    }
?>
<link rel="stylesheet" href="../Style/ProductDetails.css" media="screen"> 
<section class="u-clearfix u-section-1" id="sec-2cca">
      <div class="u-carousel u-carousel-duration-2000 u-expanded-width u-gallery u-gallery-slider u-layout-carousel u-lightbox u-no-transition u-show-text-none u-gallery-1" data-interval="5000" data-u-ride="carousel" id="carousel-68ba" data-pause="false">
      <?php if($totalSlides>0):?> 
        <div class="u-carousel-inner u-gallery-inner" role="listbox">
          <div class="u-active u-carousel-item u-gallery-item u-carousel-item-1">
            <div class="u-back-slide" data-image-width="2048" data-image-height="1261">
              <img class="u-back-image u-expanded" src="<?=$listSlides[0]->getFileName()?>">
            </div>
          </div>
          <?php if($totalSlides>1):?>
            <div class="u-carousel-item u-gallery-item u-carousel-item-2">
              <div class="u-back-slide" data-image-width="2048" data-image-height="1270">
                <img class="u-back-image u-expanded" src="<?=$listSlides[1]->getFileName()?>">
              </div>
            </div>
          <?php endif;?>
          <?php if($totalSlides>2):?>
            <div class="u-carousel-item u-gallery-item u-carousel-item-3" >
              <div class="u-back-slide" data-image-width="2048" data-image-height="1259">
                <img class="u-back-image u-expanded" src="<?=$listSlides[2]->getFileName()?>">
              </div>
              <style data-mode="XL" data-visited="true"></style>
              <style data-mode="LG"></style>
              <style data-mode="MD"></style>
              <style data-mode="SM"></style>
              <style data-mode="XS"></style>
            </div>
          <?php endif;?>
          <?php if($totalSlides>3):?>
            <div class="u-carousel-item u-gallery-item u-carousel-item-4" >
              <div class="u-back-slide" data-image-width="2048" data-image-height="1230">
                <img class="u-back-image u-expanded" src="<?=$listSlides[3]->getFileName()?>">
              </div>
              <style data-mode="XL" data-visited="true"></style>
              <style data-mode="LG"></style>
              <style data-mode="MD"></style>
              <style data-mode="SM"></style>
              <style data-mode="XS"></style>
            </div>
          <?php endif;?>
          <?php if($totalSlides>4):?>
            <div class="u-carousel-item u-gallery-item u-carousel-item-5">
              <div class="u-back-slide" data-image-width="2048" data-image-height="1267">
                <img class="u-back-image u-expanded"src="<?=$listSlides[4]->getFileName()?>">
              </div>
              <style data-mode="XL" data-visited="true"></style>
              <style data-mode="LG"></style>
              <style data-mode="MD"></style>
              <style data-mode="SM"></style>
              <style data-mode="XS"></style>
            </div>
          <?php endif;?>
        </div>
      <?php endif;?>  
      </div>
      <div class="u-container-style u-expanded-width-xs u-gradient u-group u-shape-rectangle u-group-1" data-animation-name="customAnimationIn" data-animation-duration="1000">
        <div class="u-container-layout u-container-layout-1">
          <h3 class="u-custom-font u-font-eternal u-text u-text-white u-text-1 "><?=$product->getNameProduct()?></h3>
            <ul class="u-custom-font u-custom-list u-file-icon u-font-oswald u-spacing-0 u-text u-text-body-alt-color u-text-2">
              <?php if(!empty($descriptionlist)): foreach($descriptionlist as $line){?>
                <?php if(strlen(trim($line))!=0):?>
                  <li style="padding-bottom:5px" class="u-item">
                    <div class="u-list-icon u-text-white">
                      <img src="../Images/Website/bullet-point.png" alt="" style="font-size: 1.2rem; margin: 0.3rem 2rem -1.0rem 0rem">
                    </div><span style="padding-left:5px"><?=$line?></span>
                  </li>
                  <?php endif;?>
              <?php } endif;?>
            </ul>
          </div>
          <div style="position:absolute;bottom:5px;left:20px">
          <?php if($product->getDiscount()>0.00): ?>
            <h1 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-3" style="text-decoration:line-through"><?=number_format($product->getPrice(),0,',','.')?> VND</h1>
            <h1 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-4"><?=number_format($product->getPriceAfterDiscount(),0,',','.')?> VND</h1>
          <?php else: ?>
            <h1 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-3"></h1>
            <h1 class="u-custom-font u-font-spy-agency u-text u-text-white u-text-4"><?=number_format($product->getPrice(),0,',','.')?> VND</h1>
          <?php endif; ?>
          <div class="u-form u-form-1 ">
          <?php if($product->getState()=="NEW") {?>
              <form method="post" style="padding: 15px" >
                <div class="u-clearfix u-form-horizontal u-form-spacing-15 u-inner-form">
                  <div class="u-form-group u-form-name u-label-none ">
                    <input style="background-color :white" type="number" min="1" max="<?=$product->getQuantity();?>" placeholder="Quantity" id="name-558c" name="quantity" class="u-custom-font u-font-spy-agency u-text-black u-input u-input-rectangle" required="">
                  </div>
                  <div class="u-form-group u-form-submit u-label-none">
                    <a href="#" class="u-active-black u-border-none u-btn u-btn-submit u-button-style u-custom-color-7 u-custom-font u-font-spy-agency u-hover-white u-text-active-white u-btn-1">ADD TO CART</a>
                    <input type="submit" name="submit" value="submit" class="u-form-control-hidden">
                  </div>
                </div>
              </form>
            <?php } else {?>
              <div class="u-clearfix u-form-horizontal u-form-spacing-15 u-inner-form" style="padding: 15px">
                <div class="u-form-group u-form-submit u-label-none">
                      <a href="#" class="u-active-black u-border-none u-btn u-btn-submit u-button-style u-custom-color-7 u-custom-font u-font-spy-agency u-hover-white u-text-active-white u-btn-1">OUT OF STOCK</a>
                </div>
              </div>
            <?php }?>
          </div>

          </div>
        </div>
      </div>
    </section>
<?php require "../include/footer.php";
if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST["submit"])){
    switch($success)
    {
        case 1:
            $message='Successfully added!';
            $gif='../Images/Website/success.gif';
            $color='#34c759';
            break;
        case 0:
            $message='Sorry! We can not add this product to your cart. :(';
            $gif='../Images/Website/fail.gif';
            $color='#d00539';
            break;
        default:
            $message='Oops! Something went wrong';
            $gif='../Images/Website/fail.gif';
            $color='#d00539';
            break;
    }
    Method::getPopUpAnnouncement($color,$gif,$message);
}?>