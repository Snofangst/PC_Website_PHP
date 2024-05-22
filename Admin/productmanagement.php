<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    $page=$_GET['page']??1;
    $product_per_page=10;
    $offset=0;
    $limit=0;
    $location="../Admin/productmanagement.php";
    $link='?';
    $searchFill="";
    if(!isset($_GET['search'])&&!isset($_GET['brand'])&&!isset($_GET['catalog']))
    {
        $total=Product::getTotalProduct();
        $totalpages=ceil($total['total']/$product_per_page);
        $limit=$product_per_page;
        $offset=($page-1)*$product_per_page;
        $productlist=Product::getProductsPerPage($offset,$limit,'ADMIN');
        if($page>$totalpages)
            header("location:". $location.$link."page=".$totalpages);
        else if ($page<=0)
            header("location:".$location.$link."page=1");
    }
    else if(isset($_GET['search'])&&isset($_GET['catalog'])&&isset($_GET['search']))
    {
        if(empty($_GET['search'])&&empty($_GET['catalog'])&&empty($_GET['brand']))
            header("location:".$location);
        else
        {
            $catalog=$_GET['catalog']??"";
            $name=$_GET['search']??"";
            $brand=$_GET['brand']??"";
            $searchFill=$name;
            $count=0;
            $total=Product::getTotalProductWithCondition($name,$catalog,$brand,'ADMIN');
            $location='../Admin/productmanagement.php?search='.$name.'&catalog='.$catalog.'&brand='.$brand;
            if($total['total']>0)
            {
                $link="&";
                $totalpages=ceil($total['total']/$product_per_page);
                $limit=$product_per_page;
                $offset=($page-1)*$product_per_page;
                $productlist=Product::getProductWithCondition($name,$catalog,$brand,$offset,$limit,'ADMIN');
                if($page>$totalpages)
                    header("location:".$location.$link."page=".$totalpages);
                else if ($page<=0)
                    header("location:".$location.$link."page=1");
            }
        }
    }
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(isset($_POST['delete']))
        {
            Method::showWarning("Do you really want to delete this product!");
            $_SESSION['idproductmanagement']=$_POST['id'];
        }
        if(isset($_POST['yes'])&&isset($_SESSION['idproductmanagement']))
        {
            $result=Product::checkProductInOrderDetails($_SESSION['idproductmanagement']);
            if($result==1)
            {
                $images=new Image();
                $listimage=$images->getAllImageByIdProduct($_SESSION['idproductmanagement']);
                if(!empty($listimage))
                {
                    foreach($listimage as $image)
                        $image->deleteImage($image->getIdImage(),$image->getFileName());
                }
                $pro=new Product();
                if($pro->deleteProduct($_SESSION['idproductmanagement'])==1)
                {
                    Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Successfully deleted!");
                    header("Refresh:0");
                }
                else
                    Method::getPopUpAnnouncement();
            }
            else if($result==0)
                Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',"Invalid ID!");
            else
                Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',"Can't delete purchased product!");
            unset($_SESSION['idproductmanagement']);
        }
        else if(!isset($_POST['delete'])&&isset($_SESSION['idproductmanagement']))
            unset($_SESSION['idproductmanagement']);
    }
    $listCatalog=Catalog::getAllCatalogs();
    $listBrand=Brand::getAllBrands();
?>
<style>
    .pagination a:hover:not(.active) {
        background-color:gray;
        color:white;}
</style>
<link rel="stylesheet" href="../Style/ProductManagement.css" media="screen">
    <section class="u-align-center u-clearfix u-section-1" id="sec-6b8d">
        <div class="u-clearfix u-sheet u-sheet-1">
            <div class="u-form u-form-1" >
                <form enctype="multipart/form-data" method="get">
                    <div class="u-clearfix u-form-horizontal u-form-spacing-15 u-inner-form" style="padding: 15px;" source="email">
                        <div class="u-form-group u-form-name u-label-none">
                            <input list="ListName" class="u-border-1 u-border-grey-30 u-custom-font u-font-oswald u-input u-input-rectangle u-input-1" id="name-ef64" name="search" placeholder="Search products" type="text" value="<?=$searchFill?>"/>
                        </div>
                        <div class="u-form-group u-form-submit">
                            <a href="#" class="u-active-black u-black u-border-none u-btn u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-palette-5-base u-btn-1">
                                Find<br>
                            </a>
                            <input type="submit" value="submit" class="u-form-control-hidden">
                        </div>
                    </div>
                    <div class="u-clearfix u-form-horizontal u-form-spacing-15 u-inner-form" style="padding: 15px;margin-top:-20px">
                        <div class="u-form-select-wrapper u-margin-form">
                            <select class="u-border-1 u-custom-font u-font-oswald u-border-grey-30 u-input u-input-rectangle u-white u-input-2" id="select-48f8" name="catalog">
                                <option value =""></option>
                                <?php if(!empty($listCatalog)){ foreach($listCatalog as $catalog){?>
                                    <option <?php if ( isset($_GET['catalog']) &&$catalog->getIdCatalog()==$_GET['catalog']){ echo'selected';} ?> value="<?= $catalog->getIdCatalog() ?>"><?=$catalog->getNameCatalog()?></option>
                                <?php }} ?>
                            </select>
                            <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
                            </svg>
                            
                        </div>
                        <div class="u-form-select-wrapper u-margin-form">
                            <select class="u-border-1 u-custom-font u-font-oswald u-border-grey-30 u-input u-input-rectangle u-white u-input-2" id="select-48f8" name="brand">
                                <option value =""></option>
                                <?php if(!empty($listBrand)){ foreach($listBrand as $brand){?>
                                    <option <?php if(isset($_GET['brand']) && $brand->getIdBrand()==$_GET['brand']){ echo'selected';} ?> value="<?= $brand->getIdBrand() ?>"><?=$brand->getNameBrand()?></option>
                                <?php }}?>
                            </select>
                            <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
                            </svg>
                            
                        </div>
                            <a class="u-active-black u-black u-border-none u-btn  u-button-style u-custom-font u-font-oswald u-hover-palette-5-base u-btn-1" style="margin-top:0;margin-bottom:0"onclick="location.href='../Admin/addproductform.php'">
                                Add
                            </a>

                    </div>
                </form>
            </div>
           
            <p class=" u-custom-font u-font-oswald u-text u-text-default u-text-1">
                <span class="u-file-icon u-icon u-icon-1">
                    <img src="../Images/Website/guarantee.png" alt="">
                </span>&nbsp;Product Management
                
            </p>
            <p class="u-custom-font u-font-oswald u-text u-text-default u-text-2">
                <span style="font-weight: normal;">All</span>(<?=$total['total']??0?>) | 
                <span style="font-weight: normal;">NEW</span>(<?=$total['new']??0?>) | 
                <span style="font-weight: normal;">HIDDEN</span>(<?=$total['hidden']??0?>) |
                <span style="font-weight: normal;">OUT OF STOCK</span>(<?=$total['out of stock']??0?>)
                
            </p>
            <div class="u-expanded-width u-table u-table-responsive u-table-1">
                <table class="u-table-entity u-table-entity-1">
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                        <col width="16%">
                        <col width="9%">
                        <col width="10%">
                    </colgroup>
                    <thead class="u-black u-custom-font u-font-oswald u-table-header u-table-header-1">
                        <tr style="height: 54px;text-align:center">
                            <th class="u-border-1 u-border-black u-table-cell">Image</th>
                            <th class="u-border-1 u-border-black u-table-cell">ID</th>
                            <th class="u-border-1 u-border-black u-table-cell">Name</th>
                            <th class="u-border-1 u-border-black u-table-cell">Catalog</th>
                            <th class="u-border-1 u-border-black u-table-cell">Brand</th>
                            <th class="u-border-1 u-border-black u-table-cell">Price</th>
                            <th class="u-border-1 u-border-black u-table-cell">Quantity</th>
                            <th class="u-border-1 u-border-black u-table-cell">State</th>
                        </tr>
                    </thead>
                    <tbody class="u-custom-font u-font-oswald u-table-body u-table-body-1">
                        <?php if(!empty($productlist)){ foreach($productlist as $product) :?>
                        <tr style="height: 142px; text-align:center">
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                                <img class="cell-image" src="<?=$product->getImage()?>" alt=""/>
                            </td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$product->getIdProduct()?></td>
                            <td  class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                                <span><?=$product->getNameProduct()?><br/>
                                    <a href="../Admin/productdetails.php?id=<?=$product->getIdProduct()?>">Details |</a>
                                    <a>
                                        <form method="post" style="all:unset">
                                            <input value="<?=$product->getIdProduct()?>" type="text" name="id" style="display:none"></input>
                                            <input style="border:unset;background-color:unset;cursor: pointer;padding:0px" value="Delete" type="submit" name="delete"></input>
                                         </form>
                                    </a>
                                </span>
                            </td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$product->getIdCatalog()?></td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$product->getIdBrand()?></td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=number_format($product->getPrice(),0,',','.')?> VND</td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$product->getQuantity()?></td>
                            <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$product->getState()?></td>
                        </tr>
                        <?php endforeach; }?>
                          
                    </tbody>
                </table>
                <?php if(empty($productlist)){?><h1 class="u-custom-font u-font-oswald"> THERE IS NO PRODUCT!</h1>
                    <?php   }?>
            </div>
        </div>
    </section>
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

<?php 
    require '../include/footer.php';
?>