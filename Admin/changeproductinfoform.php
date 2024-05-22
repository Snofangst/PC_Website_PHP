<?php
    require '../include/init.php';
    require "../include/header_admin.php";
    $NameError=null;
    $PriceError=null;
    $QuantityError=null;
    $ImageError=null;
    $quote=array('"',"'");
    $success=0;
    $confirmed=0;
    $NameFill=$PriceFill=$DescriptionFill=null;
    $QuantityFill=$DiscountFill=0.0;
    $StateChoice=$CatalogChoice=$BrandChoice=null;
    $ImageFill=null;
    
    $mime_types = ['image/jpg', 'image/jpeg', 'image/png','image/webp'];
    if(isset($_SESSION['success']))
    {
        switch($_SESSION['success'])
        {
            case 0:
                $message='Oops! Unknown Error!';
                $gif='../Images/Website/fail.gif';
                $color='#d00539';
                break;
            case 1:
                $message='Change info successfully!';
                $gif='../Images/Website/success.gif';
                $color='#34c759';
                break;
            case 2:
                $message='Oh! There is nothing to change.';
                $gif='../Images/Website/success.gif';
                $color='#34c759';
                break;
            default:
                $message='Oops! Something went wrong';
                $gif='../Images/Website/fail.gif';
                $color='#d00539';
                break;
        }
        Method::getPopUpAnnouncement($color,$gif,$message);
        unset($_SESSION['success']);
    }
    if(isset($_GET['id']))
    {
        $product=Product::getProductById($_GET['id']);
        if(empty($product))
            header("../Admin/error.php");
        else
        {
            $listCatalog=Catalog::getAllCatalogs();
            $listBrand=Brand::getAllBrands();
            $NameFill=$product->getNameProduct();
            $PriceFill=number_format($product->getPrice(),0,',','.');
            $DescriptionFill=preg_replace( "~#~","\n",$product->getDescription() );
            $QuantityFill=$product->getQuantity();
            $DiscountFill=$product->getDiscount();
            $StateChoice=$product->getState();
            $BrandChoice=$product->getIdBrand();
            $CatalogChoice=$product->getIdCatalog();
            $ImageFill=$product->getImage();
            $image=new Image();
        }
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            if(isset($_POST['deleteimage_x'],$_POST['deleteimage_y']))
            {
                $ImageFill=NULL;
                $_SESSION['confirmeddelete']=1;
            }
            else if (isset($_POST['submit']))
            {
                $_POST['nameproduct']=str_replace($quote,"",$_POST['nameproduct']);
                $_POST['description']=str_replace($quote,"",$_POST['description']);
                if(!is_numeric(str_replace('.','',$_POST['price'])))
                    $PriceError="Please use the digits 0 to 9!";
                else
                {
                    $price=(float)(str_replace('.','',$_POST['price']));
                    if($price<1000)
                        $PriceError="Price must be greater than 1.000 VND !";
                    else if($price%1000!=0)
                        $PriceError="Price must be divided by 1.000 VND !";
                }
                if($_POST['nameproduct']!=$product->getNameProduct()&&Product::validateName($_POST['nameproduct'])==false)
                    $NameError="Product name has been taken!";
                if(isset($_FILES['image']))
                {
                    switch($_FILES['image']['error'])
                    {
                        case UPLOAD_ERR_OK:
                        {
                            if($_FILES['image']['size']>20000000)
                                $ImageError="File is too large!";
                            else if($ImageError==null&&$_FILES['image']['size']!=0)
                            {
                                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                                $mime_type = finfo_file($file_info, $_FILES['image']['tmp_name']);
                                if (!in_array($mime_type, $mime_types)) {
                                    $ImageError="Invalid file type!";
                                }
                                else 
                                {
                                    $pathinfo=pathinfo($_FILES['image']['name']);
                                    $fname=$_GET['id'];
                                    $extension=$pathinfo['extension'];
                                    $dest = '../Images/Products/' . $fname . '.' . $extension;
                                    try{
                                        $image->setFileName($dest);
                                        $image->setIdImage(Image::getNewId());
                                    }
                                    catch(Exception $ex)
                                    {
                                        $ImageError="Can't upload these images!";
                                    }
                                }
                            }
                        }
                        case UPLOAD_ERR_NO_FILE:
                            break;
                        default:
                            $ImageError="Cann't upload this file!";
                    }
                }
                if(empty($NameError)&&empty($PriceError)&&empty($ImageError))
                {
                    $update=new Product();
                    $quantity=$discount=$price=0;
                    $update->setIdProduct($_GET['id']);
                    if($_POST['nameproduct']!=$product->getNameProduct())
                        $update->setNameProduct($_POST['nameproduct']);
                    if($_POST['state']!=$product->getState())
                        $update->setState($_POST['state']);
                    if($_POST['idcatalog']!=Catalog::getIdByName($product->getIdCatalog())['idcatalog'])
                        $update->setIdCatalog($_POST['idcatalog']);
                    if($_POST['idbrand']!=Brand::getIdByName($product->getIdBrand())['idbrand'])
                        $update->setIdBrand($_POST['idbrand']);
                    $update->setDescription(preg_replace( '[\r\n]', '#', $_POST['description']));
                    if($product->getPrice()!=floatval(str_replace('.','',$_POST['price'])))
                        $update->setPrice(str_replace('.','',$_POST['price']));
                    if($product->getQuantity()!=$_POST['quantity'])
                        $update->setQuantity($_POST['quantity']);
                    if($product->getDiscount()!=$_POST['discount'])
                        $update->setDiscount($_POST['discount']);
                    $success=$update->updateProduct();
                    $detail=new ImageDetails();
                    $id= $detail->getIdPostImageByIdProduct($update->getIdProduct());
                    try{
                        if(empty($_FILES['image']['name'])&&$product->getImage()!="../Images/Products/Error.png"&&isset($_SESSION['confirmeddelete']))
                        {
                            $image->deleteImage($id,$product->getImage());
                            $success=1;
                            unset($_SESSION['confirmeddelete']);
                        }
                        else
                        {
                            if(empty($id)&&strlen($image->getFileName())!=0)
                            {
                                $image->insert_Image();
                                $details=new ImageDetails($image->getIdImage(),$product->getIdProduct(),'POST');
                                $details->insert_ImageDetails();
                                move_uploaded_file($_FILES['image']['tmp_name'], $image->getFileName());
                                $success=1;
                            }
                            else if(!empty($id)&&strlen($image->getFileName())!=0)
                            {
                                $image->setIdImage($id);
                                $image->updateImage();
                                unlink($product->getImage());
                                move_uploaded_file($_FILES['image']['tmp_name'], $image->getFileName());
                                $success=1;
                            }
                        }
                    }catch(Exception $ex)
                    {
                        echo '<script>alert("'.$ex->getMessage().'")</script>';
                        Method::getPopUpAnnouncement();
                    }
                    if($success!=0)
                    {
                        $_SESSION['success']=$success;
                        header("Refresh:0");
                    }
                }
            }
        }
    }
    else
        header("../Admin/error.php");
?>
<link rel="stylesheet" href="../Style/Add_Products.css" media="screen">
<section class="u-align-center u-clearfix u-image u-uploaded-video u-video-contain-lg u-video-contain-md u-video-contain-sm u-video-contain-xs u-section-1" id="carousel_c8a8" data-image-width="1920" data-image-height="1080">
<div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
    <div class="u-align-center u-border-1 u-border-black u-container-style u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-group u-radius-49 u-shape-round u-group-1">
        <div class="u-container-layout u-container-layout-1">
            <h2 class="u-custom-font u-text u-text-body-alt-color u-text-1">CHANGE REQUEST</h2>
            <div class="u-form u-form-1">
                <form enctype="multipart/form-data" method="post">
                    <input type="hidden" style="display:none" class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-1" id="name-3b9a" name="nameproduct" placeholder="Enter product name"  value="" />
                    <div class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" style="padding: 10px" source="email" name="form">
                        <div class="u-form-group u-form-name u-form-partition-factor-2 u-label-top">
                            <label for="name-3b9a" class="u-label u-text-white u-label-1">Product name(*)</label>
                            <span class="field-validation-valid text-danger"style="color:red;font-weight:bold;text-shadow: 1px 1px black;"><?=$NameError?></span>
                            <input onkeypress="return checkQuote();" onkeyup="return ignoreQuote(this);" class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-1" id="io1" name="nameproduct" placeholder="Enter product name" type="text" value="<?=$NameFill?>" />
                        </div>
                        <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-2">
                            <label for="select-48f8" class="u-label u-text-white u-label-2">State (*) </label>
                            <span style="color:red;font-weight:bold;text-shadow: 1px 1px black;"></span>
                            <div class="u-form-select-wrapper">
                                <select class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-2" id="select-48f8" name="state" onclick="return checkQuantity(this)">
                                    <option <?php if($StateChoice=='NEW') echo 'selected'?>  value="NEW">NEW</option>
                                    <option <?php if($StateChoice=='OOS') echo 'selected'?>  value="OOS">OUT OF STOCK</option>
                                    <option <?php if($StateChoice=='HIDDEN') echo 'selected'?>  value="HIDDEN">HIDDEN</option>
                                </select>
                                <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                                    <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
                                </svg>
                            </div>
                        </div>
                        <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-3">
                            <label for="select-1ae3" class="u-label u-text-white u-label-3">Catalog (*) </label>
                            <span class="field-validation-valid text-danger" style="color:red;font-weight:bold;text-shadow: 1px 1px black; "></span>
                            <div class="u-form-select-wrapper">
                                <select class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-3"  id="select-1ae3" name="idcatalog">
                                    <?php foreach($listCatalog as $catalog){?>
                                        <option <?php if($CatalogChoice==$catalog->getNameCatalog()) echo 'selected' ?> value="<?= $catalog->getIdCatalog() ?>"><?=$catalog->getNameCatalog()?></option>
                                    <?php }?>
                                </select>
                                <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                                    <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
                                </svg>
                            </div>
                        </div>
                        <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-4">
                            <label for="select-38a0" class="u-label u-text-white u-label-4">Brand (*) </label>
                            <span class="field-validation-valid text-danger" style="color:red;font-weight:bold;text-shadow: 1px 1px black; "></span>
                            <div class="u-form-select-wrapper">
                                <select class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-4"  id="select-38a0" name="idbrand">
                                <?php foreach($listBrand as $brand){?>
                                        <option <?php if($BrandChoice==$brand->getNameBrand()) echo 'selected' ?> value="<?= $brand->getIdBrand() ?>"><?=$brand->getNameBrand()?></option>
                                    <?php }?>
                                </select>
                                <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                                    <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
                                </svg>
                            </div>
                        </div>
                        <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-5">
                            <label for="text-f6e9" class="u-label u-text-white u-label-5">Price (*) </label>
                            <span class="field-validation-valid text-danger" style="color:red;font-weight:bold;text-shadow: 1px 1px black; "><?=$PriceError?></span>
                            <input class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-5" id="text-f6e9" max="1000000000" min="10000" name="price" onkeyup="javascript:this.value=Comma(this.value);" placeholder="Enter product price" type="text" value="<?=$PriceFill?>" />
                        </div>
                        <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-6">
                            <label for="text-2bef" class="u-label u-text-white u-label-6">Discount </label>
                            <span class="field-validation-valid text-danger" style="color:red;font-weight:bold;text-shadow: 1px 1px black; "></span>
                            <input class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-6"  id="text-2bef" max="1.0" min="0.0" name="discount" placeholder="Enter discount" step="0.01" type="number" value="<?=$DiscountFill?>"/>
                        </div>
                        <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-7">
                            <label for="text-769a" class="u-label u-text-white u-label-7">Quantity (*)</label>
                            <span class="field-validation-valid text-danger"  style="color:red;font-weight:bold;text-shadow: 1px 1px black; "></span>
                            <input  oninput="return checkState(this)" class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-7" id="text-769a" max="999" min="0" name="quantity" placeholder="Enter quantity " step="1" type="number" value="<?=$QuantityFill?>" />
                        </div>
                        <div class="u-form-group u-form-message u-label-top">
                            <label for="message-3b9a" class="u-label u-text-white u-label-8">Description</label>
                            <textarea onkeypress="return checkQuote()" onkeyup="return ignoreQuote(this);" class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-8" cols="50" id="io" name="description" placeholder="Enter description" rows="5"><?=$DescriptionFill?></textarea>
                        </div>

                        <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-9">
                            
                            <label for="text-ecf2" class="u-label u-text-white u-label-9">Product Image</label>
                            <?php if($ImageFill==NULL||$ImageFill=="../Images/Products/Error.png"){?>
                                <span class="field-validation-valid u-label"   style="color:red;font-weight:bold;text-shadow: 1px 1px black;"><?=$ImageError?></span>
                                <input accept="image/png, image/jpg, image/jpeg" class="u-border-2 u-border-grey-30 u-input u-input-rectangle u-radius-10 u-white u-input-9" id="text-ecf2" name="image" type="file" />
                            <?php }else {?>
                                <img class="u-border-2 u-border-grey-30 u-radius-10" style="width: 220px;height:220px"src="<?=$ImageFill?>">
                                <form style="all:unset" method="post">
                                    <input name="deleteimage" type="image" src="../Images/Website/gray-exit.png" onmouseleave="this.src='../Images/Website/gray-exit.png'" onclick="this.form.submit()" onmouseover="this.src='../Images/Website/white-exit.png'" class="btn-delete">
                                    <input name="confirmdelete" value="1" type="hidden">
                                </form>
                            <?php }?>
                        </div>
                        <div class="u-align-center u-form-group u-form-submit u-label-top">
                            <a href="#" class="u-active-white u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-palette-5-light-2 u-radius-10 u-text-active-black u-text-hover-black u-white u-btn-1">
                                Confirm<br>
                            </a>
                            <input type="submit" value="submit" name="submit" class="u-form-control-hidden">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
</body>
<script>
    function checkQuote() {
        if(event.keyCode == 39 || event.keyCode == 34) {
            event.keyCode = 0;
            return false;
        }
    }
    function checkQuantity(selectstate)
    {
        quantityValue=document.getElementById('text-769a');
        if(selectstate.value=="NEW"&&quantityValue.value<=0)
            quantityValue.value=1;
        else if(selectstate.value=="OOS")
            quantityValue.value=0;
    }
    function checkState(quantityValue)
    {
        stateSeleted=document.getElementById('select-48f8');
        if(quantityValue.value==0)
            stateSeleted.value="OOS";
        else if(quantityValue.value>0)
            stateSeleted.value="NEW";
    }
</script>
<?php require "../include/footer.php"?>