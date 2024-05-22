<?php 
    require '../include/init.php';
    $color=$_SESSION['color'];
    $page=$_GET['page']??1;
    $product_per_page=8;
    $background='../Images/Website/graffiti';
    $location="../Home/product.php";
    $link='?';
    if((isset($_GET['catalog'])||isset($_GET['search']))&&$_SERVER["REQUEST_METHOD"]=="GET")
    {
        $name="";
        $catalog="";
        $brand="";
        if(!empty($_GET['search']))
        {
            $id=Brand::getIdByName($_GET['search']);
            if(empty($id))
                $name=$_GET['search'];
            else
                $brand=$id['idbrand'];
            $location=$location."?search=".$_GET['search'];
            
        }
        if(!empty($_GET['catalog']))
        {
            $id=Catalog::getIdByName($_GET['catalog']);
            if(!empty($id))
                $catalog=$id['idcatalog'];
            if(isset($_GET['search']))
                $location.="&";
            else
                $location.="?";
            $location=$location."catalog=".$_GET['catalog'];
        }
        $total=Product::getTotalProductWithCondition($name,$catalog,$brand,NULL)['total'];
    }
    $title="PRODUCT";
    if(isset($_GET['catalog']))
    {
        
        switch(strtolower($_GET['catalog']))
        {
            case 'pc':
                $title="PC";
                $background.='2.png';
                break;
            case 'mouse':
                $title="MOUSE";
                $background.='3.png';
                break;
            case 'monitor':
                $title="MONITOR";
                $background.='4.png';
                break;
            case 'keyboard':
                $title="KEYBOARD";
                $background.='5.png';
                break;
            default:
                $title="PRODUCT";
                $background.='.jpg';
                break;
        }
    }
    else
        $background.='.jpg';
    echo"<title>".$title." - ZEUS'S PC</title>";
    require "../include/header.php";
    if(!isset($_GET['catalog'])&&!isset($_GET['search']))
    {
        $totalALL=Product::getTotalProduct();
        $total=$totalALL['total']-$totalALL['hidden'];
        $totalpages=ceil($total/$product_per_page);
        $limit=$product_per_page;
        $offset=($page-1)*$product_per_page;
        $listcards=Product::getProductsPerPage($offset,$limit,NULL);
        if($page>$totalpages)
            header("location:". $location.$link."page=".$totalpages);
        else if ($page<=0)
            header("location:".$location.$link."page=1");
    }
    else if(isset($total)&&$total>0)
    {
        $link="&";
        $totalpages=ceil($total/$product_per_page);
        $limit=$product_per_page;
        $offset=($page-1)*$product_per_page;
        $listcards=Product::getProductWithCondition($name,$catalog,$brand,$offset,$limit,NULL);
        if($page>$totalpages)
            header("location:".$location.$link."page=".$totalpages);
        else if ($page<=0)
            header("location:".$location.$link."page=1");
    }
    // echo $total;
?>
<link rel="stylesheet" href="../Style/Products.css" media="screen">
<style>
    .pagination a:hover:not(.active) {background-color: <?=$color?>;}
</style>
<section class="u-clearfix u-gradient u-section-2" id="sec-9de5" style="background-image:url(<?=$background?>);">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-expanded-width-lg u-expanded-width-md u-expanded-width-xl u-list u-list-1">
            <div class="u-repeater u-repeater-1">
                <?php if(!empty($listcards)){ foreach($listcards as $card){ ?>
                    <div style=" cursor:pointer;" class="u-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle"onclick="location.href='../Home/details.php?idproduct=<?= $card->getIdProduct()?>';" >
                        <div class="u-container-layout u-similar-container u-valign-top-lg u-valign-top-xl u-container-layout-2">
                            <div class="u-align-left u-container-style u-group u-radius-10 u-shape-rectangle u-white u-group-2">
                            <?php if($card->getState()=='OOS'){?><img style="width:40%;position:absolute;z-index: 1;right:0;left:60%" src="../Images/Website/soldout.png">
                            <?php }else if($card->getDiscount()>0.00){ ?><img style="width:40%;position:absolute;z-index: 1;right:0;left:60%" src="../Images/Website/onsale.png"><?php } ?>
                                <div class="u-container-layout u-container-layout-3">
                                    <img class="u-border-2 u-border-white u-image u-image-round u-preserve-proportions u-radius-17 u-image-1" src="<?= $card->getImage(); ?>" alt="" data-image-width="219" data-image-height="219">
                                    <h4 class="u-text u-text-2 u-custom-font u-font-spy-agency" ><?=$card->getNameProduct(); ?></h4>
                                    <?php if($card->getDiscount()>0.00):?>
                                        <p class="u-custom-font u-font-spy-agency u-text u-text-3">
                                            <span style="text-decoration:line-through"><?=number_format($card->getPrice(),0,',','.');?> vnd</span>
                                            <span style="color:red">-<?= $card->getDiscount()*100; ?>%</span>
                                        </p>
                                        <p class="u-custom-font u-font-spy-agency u-text u-text-4"><?=number_format($card->getPriceAfterDiscount(),0,',','.');?> vnd</p>
                                        
                                    <?php else:?>
                                        <br/>
                                        <p class="u-custom-font u-font-spy-agency u-text u-text-4"><?= number_format($card->getPrice(),0,',','.');?> vnd </p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }} ?>         
            </div>
        </div>
    </div>
    <?php if(isset($totalpages)&&$totalpages>1){?>
    <div style="text-align: center;" >
        <div class="pagination u-custom-font u-font-spy-agency">
            <a href="<?php  if(!isset($_GET['page'])||$_GET['page']==1)
                                echo ''; 
                            else 
                                echo $location.$link.'page='.(($_GET['page'])-1) ?>">
            <</a>
            <?php for($i=1;$i<=$totalpages;$i++){ ?>
                <a <?php if((!isset($_GET['page'])&&$i==1)||(isset($_GET['page'])&&$_GET['page']==$i)) echo'class="active"'?> href="<?=$location.$link.'page='.$i ?>"><?=$i?></a>
            <?php }?>
            <a href="<?php if(!isset($_GET['page']))echo $location.$link.'page=2';else if($_GET['page']==$totalpages) echo ""; else echo $location.$link.'page='.($_GET['page']+1) ?>">></a>
        </div>
    </div>
    <?php } ?>
</section>
</body>
<?php 
    require "../include/footer.php";
?>