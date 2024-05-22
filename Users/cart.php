<title>CART - ZEUS'S PC</title>
<?php
    require '../include/init.php';
    require '../include/header.php';
    $listItems=null;
    $totalItems=0;
    $total=0;
    $paymentSelection=null;
    $idorder=null;
    if(isset($_SESSION['login']))
    {
        $idorder=Database::getIdOrderInProgressByAccId($_SESSION['login'][0]);
        if($idorder!=null)
        {
            if($_SERVER['REQUEST_METHOD']=="POST")
            {
                if(isset($_POST["delete"]))
                {
                    $detail=new OrderDetails($idorder,$_POST['idproduct'],0);
                    $detail->deleteItem();
                }
                else if(isset($_POST["quantity"]))
                {
                    if($_POST["quantity"]!=null)
                    {
                        $detail=new OrderDetails($idorder,$_POST['idproduct'],$_POST['quantity']);
                        $detail->updateItem();
                    }
                    else if($_POST["quantity"]==null)
                    {
                        $detail=new OrderDetails($idorder,$_POST['idproduct'],0);
                        $detail->deleteItem();
                    }
                }
            }
            $listItems=Cart::getItemsFromCartByAccID($_SESSION['login'][0]);
        }
    }
    else if(isset($_COOKIE['cart']))
    {
        $cart= unserialize($_COOKIE['cart'], ["allowed_classes" => true]);
        if($_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['idproduct']))
        {
            $key=Method::findItemInCookie($cart,$_POST['idproduct']);
            if($key!=null||$key===0)
            {
                if(isset($_POST["delete"]))
                    unset($cart[$key]);
                else if(isset($_POST["quantity"]))
                {
                    if($_POST["quantity"]!=null)
                    {
                        if($_POST['quantity']>0)
                            $cart[$key]->setQuantity($_POST['quantity']);
                        else
                             unset($cart[$key]);
                    }
                    else if($_POST["quantity"]==null)
                        unset($cart[$key]);
                }
                setcookie('cart', serialize($cart), time() + (86400 * 30),"/");
            }
        }
        if(!empty($cart))
            $listItems=Cart::getItemsFromCookie($cart);
    }
    if(!empty($listItems))
    {
        foreach($listItems as $item)
        {
            $total+=($item->getPriceAfterDiscount()*$item->getQuantity());
            $totalItems+=$item->getQuantity();
        }
    }
    if($_SERVER['REQUEST_METHOD']=="POST"&& isset($_POST['checkout']))
    {
        try{
            $paymentSelection=$_POST['payment'];
            $order =new Orders();
            $countProduct=0;
            $totalProduct=count($listItems);
            $deleteItem=null;
            $ErrorString="";
            foreach($listItems as $item)
            {
                if(Product::checkExistedandAvailable($item->getIdProduct(),$item->getQuantity())==1)
                    $countProduct++;
                else
                {
                    $deleteItem[]=$item;
                    $ErrorString.=" ".$item->getNameProduct();
                }
            }
            if($countProduct==$totalProduct)
            {
                if(!empty($idorder)&&!empty($listItems))
                {
                    try{
                    $order->updateOrder($idorder,date("Y-m-d H:i:s"),$_POST['payment'],$total);;
                    foreach($listItems as $item)
                    {
                        $detail=new OrderDetails();
                        $detail->updateDetails($idorder,$item->getIdProduct(),$item->getQuantity(),$item->getPrice(),$item->getDiscount());
                    }
                    $_SESSION['idorder']=$idorder;
                    $_SESSION['listitems']=$listItems;
                    $_SESSION['total']=$total;
                    header("location: ../Users/delivery_form.php");}
                    catch(Exception $ex)
                    {
                        echo '<script>alert("'.$ex->getMessage().'")</script>';
                    }
                }
                else if(isset($_COOKIE['cart'])&&!empty($listItems))
                {
                    $_SESSION['information']=array($paymentSelection,$total);
                    $_SESSION['listitems']=$listItems;
                    header("location: ../Users/delivery_form.php");
                }
            }
            else
            {
                if(count($deleteItem)>1)
                    Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$ErrorString.'.<br/> Oops! These products went low in quantity.');
                else
                    Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$ErrorString.'<br/>Oops! This product went low in quantity.');
                $listItems=array_udiff($listItems,$deleteItem, function ($a, $b) {return strcmp($a->getIdProduct(), $b->getIdProduct());});
                if(!empty($idorder)&&!empty($deleteItem))
                {
                    foreach($deleteItem as $item)
                    {
                        $detail=new OrderDetails($idorder,$item->getIdProduct(),0);
                        $detail->deleteItem();
                    }
                }
                else if(isset($_COOKIE['cart'])&&!empty($deleteItem))
                {
                    foreach($deleteItem as $item)
                    {
                        $key=Method::findItemInCookie($cart,$item->getIdProduct());
                        if($key!=null||$key===0)
                            unset($cart[$key]);
                    }
                    setcookie('cart', serialize($cart), time() + (86400 * 30),"/");
                }
            }
        }
        catch(Exception $ex){
            Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif','Sorry! Something went wrong!');
        }
     
    }
?>
<link rel="stylesheet" href="../Style/Cart.css" media="screen">
<link rel="stylesheet" href="../Style/HideProdMenu.css" media="screen">
    <section class="u-align-center u-clearfix u-palette-5-light-1 u-uploaded-video u-section-1" id="sec-754a">
        <div class="u-clearfix u-sheet u-sheet-1">
            <div class="u-border-3 u-border-grey-dark-1 u-line u-line-vertical u-line-1"></div>
            <h2 class=" u-text u-text-default u-text-1"><img class="u-label-4" src="../Images/Website/52.png" ></h2>
            <a href="../Home/product.php" class=" u-active-none u-border-none u-btn u-button-style u-hover-none u-none u-text-hover-black u-btn-1">
                <img class="u-sign" src="../Images/Website/continue-shopping.png" alt="">
            </a>
            <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-list u-list-1" style="margin-top:0px">
                <div class="u-repeater u-repeater-1">
                    <?php if($listItems!=null){?>
                        <?php foreach($listItems as $item)
                        {?>
                            <div class="u-container-style u-grey-5 u-hover-feature u-list-item u-radius-20 u-repeater-item u-shape-round u-list-item-1" id=product0>
                                <div class="u-container-layout u-similar-container u-container-layout-2">
                                    <form  method="post">
                                        <input style="display:none" id="IDProduct" name="idproduct"  value="<?=$item->getIdProduct()?>"/>
                                        <div class="u-form-group u-form-submit u-label-top u-form-group-2">
                                            <button name="delete" type="submit" class="u-btn u-btn-round u-button-style u-none u-text-hover-palette-3-base u-text-palette-4-base u-btn-3" style="outline:none">
                                                <span class="u-file-icon u-icon u-text-black u-icon-2">
                                                    <img src="../Images/Website/Delete.png" alt="">
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                    <img class="u-image u-image-round u-preserve-proportions u-radius-11 u-image-2" src="<?=$item->getImage()?>" alt="" data-image-width="1280" data-image-height="860">
                                    <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-6">Name: <span ><?=$item->getNameProduct()?></span></p>
                                    <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-7">Price: <span class="price"><?=number_format($item->getPriceAfterDiscount(),0,',','.')?></span> VND</p>
                                    <form method="post">
                                        <input id="IDProduct" name="idproduct" type="hidden"  value="<?=$item->getIdProduct()?>"/>
                                        <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-8">Quantity:
                                            <input class="i-input u-custom-font u-font-spy-agency" id="quantity_0" name="quantity" min="0" max="<?=$item->getMax()?>" value="<?=$item->getQuantity()?>" onblur="this.form.submit()" type="number"></input>
                                        </p>
                                        <input name="update" type="submit" style="display:none">
                                    </form>
                                    <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-9">Subtotal: <span class="subtotal"><?=number_format($item->getPriceAfterDiscount()*$item->getQuantity(),0,',','.') ?></span> VND</p>
                                </div>
                            </div>
                        <?php }?>
                    <?php } ?>
                </div>
            </div>
            <div class="u-container-style u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-group u-radius-20 u-shape-round u-white u-group-1">
                <div class="u-container-layout u-container-layout-1">
                    <div class="u-absolute-hcenter-lg u-absolute-hcenter-xl u-border-3 u-border-grey-dark-1 u-line u-line-horizontal u-line-2"></div>
                    <div class="u-absolute-hcenter-lg u-absolute-hcenter-xl u-border-3 u-border-grey-dark-1 u-line u-line-horizontal u-line-3"></div>
                    <img class="u-image u-image-contain u-image-default u-image-1" src="../Images/Website/Zeus.png" alt="" data-image-width="317" data-image-height="354" data-animation-name="pulse" data-animation-duration="1000" data-animation-direction="">
                    <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-default u-text-2">Amount:</p>
                    <p style="font-weight:380" class="u-align-right u-custom-font u-font-spy-agency u-text u-text-3">
                        <span id="totalItems"><?=$totalItems ?></span><?php if($totalItems>=2) echo(' items'); else echo(' item')?>
                    </p>
                    <p style="font-weight:380" class="u-align-right u-custom-font u-font-spy-agency u-text u-text-4">
                        <span id="total"><?=number_format($total,0,',','.') ?></span> VND
                    </p>
                    <p style="font-weight:380" class="u-custom-font u-font-spy-agency u-text u-text-default u-text-5">Total:</p>
                    <div class="u-expanded-width-lg u-expanded-width-xl u-form u-form-1">
                        <form method="post">
                            <div action="#" class="u-clearfix u-form-spacing-15 u-form-vertical u-inner-form" style="padding: 15px;" source="email" name="form">
                                <div class="u-form-checkbox-group u-form-group u-form-input-layout-horizontal u-label-top u-form-group-1">
                                    <label style="font-weight:380" class="u-custom-font u-font-spy-agency u-label u-label-1">Payment method:</label>
                                    <div class="u-form-checkbox-group-wrapper">
                                        <div class="u-input-row">
                                            <input required <?php if($paymentSelection=='Banking') echo 'checked=checked';?> id="field-f16e" name="payment" type="radio" value="Banking"/>
                                            <label style="font-weight:380" class="u-custom-font u-font-spy-agency u-label u-label-2" >Banking</label>
                                        </div>
                                        <div class="u-input-row">
                                            <input required  <?php if($paymentSelection=='COD') echo 'checked=checked';?> id="field-0c02" name="payment" type="radio" value="COD"/>
                                            <label style="font-weight:380" class="u-custom-font u-font-spy-agency u-label u-label-3">COD</label>
                                        </div>
                                    </div>
                                </div>
                                <div style="font-weight:380" class="u-align-center u-form-group u-form-submit u-label-top u-form-group-2">
                                    <a href="#" class=" u-custom-font u-font-spy-agency u-border-none u-btn u-btn-round u-btn-submit u-button-style u-palette-3-base u-radius-10 u-btn-2 ">
                                        Check out<br>
                                    </a>
                                    <input type="submit" value="submit" name="checkout" class="u-form-control-hidden">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<?php
    require '../include/footer.php';
?>