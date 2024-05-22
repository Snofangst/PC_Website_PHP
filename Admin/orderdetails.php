<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    if(isset($_GET['id']))
    {
        $order=new Orders();
        $details=new OrderDetails();
        $order=$order->selecSpecificOrder($_GET['id']);
        if(empty($order))
            header("location:../Admin/Error.php");
        $detaillist=$details->selectDetailByOrderId($_GET['id']);
    }
    else
        header("location:../Admin/Error.php");
?>
<link rel="stylesheet" href="../Style/OrderDetails.css" media="screen">
<section class="u-align-center u-clearfix u-grey-15 u-section-1" id="sec-03a4">
<div class="u-clearfix u-sheet u-sheet-1">
    <div class="u-expanded-width u-tabs u-tabs-1">
        <ul class="u-tab-list u-unstyled" role="tablist">
            <li class="u-tab-item" role="presentation">
                <a class="active u-active-white u-button-style u-custom-font u-font-oswald u-tab-link u-tab-link-1" id="link-tab-14b7" href="#tab-14b7" role="tab" aria-controls="tab-14b7" aria-selected="true">ORDER INFORMATION</a>
            </li>
            <li class="u-tab-item" role="presentation">
                <a class="u-active-white u-button-style u-custom-font u-font-oswald u-tab-link u-tab-link-2" id="link-tab-0da5" href="#tab-0da5" role="tab" aria-controls="tab-0da5" aria-selected="false">ORDER DETAILS</a>
            </li>
        </ul>
        <div class="u-tab-content">
            <div class="u-align-left u-container-style u-tab-active u-tab-pane u-white u-tab-pane-1" id="tab-14b7" role="tabpanel" aria-labelledby="link-tab-14b7">
                <div class="u-container-layout u-container-layout-1">
                    <p class="u-custom-font u-font-oswald u-text u-text-1">
                        ORDER ID: <a style="color:red"><?=$order->getIdOrder()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-2">
                        DATE: <a style="color:red"><?php echo date('d-m-Y H:i:s', strtotime($order->getDate()));?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-3">
                        RECIPIENT NAME: <a style="color:red"><?=$order->getName()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-4">
                        PHONE NUMBER: <a style="color:red"><?=$order->getPhoneNumber()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-5">
                        USER ID: <a style="color:red"><?=$order->getIdAcc()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-6">
                        DELIVERY STATE: <a style="color:red"><?=$order->getDelivery()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-7">
                         PAYMENT: <a style="color:red"><?=$order->getPayment()?></a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-8">
                        ĐỊA CHỈ GIAO HÀNG: <a style="color:red"><?=$order->getAddress()?>
                    </a>
                    </p>
                    <div class="u-border-3 u-border-grey-dark-1 u-expanded-width-lg u-expanded-width-md u-expanded-width-xl u-line u-line-horizontal u-line-1"></div>
                    <p class="u-custom-font u-font-oswald u-text u-text-9">
                        TOTAL: <a style="color:red"><?=number_format($order->getTotal(),0,',','.')?> VND</a>
                    </p>
                    <p class="u-custom-font u-font-oswald u-text u-text-10">
                        NUMBER OF PURCHASED: <a style="color:red"><?=$order->getNumberofPurchased()?></a>
                    </p>
                </div>
            </div>
            <div class="u-container-style u-tab-pane u-white u-tab-pane-2" id="tab-0da5" role="tabpanel" aria-labelledby="link-tab-0da5">
                <div class="u-container-layout u-valign-top-lg u-valign-top-md u-valign-top-xl u-container-layout-2">
                    <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-table u-table-responsive u-table-1">
                        <table class="u-table-entity">
                            <colgroup>
                                <col width="15%">
                                <col width="30%">
                                <col width="15%">
                                <col width="20%">
                                <col width="20%">
                            </colgroup>
                            <thead class="u-align-left u-custom-font u-font-oswald u-palette-5-dark-2 u-table-header u-table-header-1">
                                <tr style="height: 52px;">
                                    <th class="u-table-cell">PROUDUCT ID</th>
                                    <th class="u-table-cell">PRODUCT NAME</th>
                                    <th class="u-table-cell">QUANTITY</th>
                                    <th class="u-table-cell">PRICE</th>
                                    <th class="u-table-cell">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="u-align-left u-custom-font u-font-oswald u-table-body u-table-body-1">
                                <?php if(!empty($detaillist)){ foreach($detaillist as $item){ $price=$item->getSavedPrice()-($item->getSavedPrice()*$item->getSavedDiscount());?>
                                    <tr style="height: 63px;">
                                        <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-first-column u-table-cell"><?=$item->getIdProduct()?></td>
                                        <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell">
                                            <a href="../Admin/productdetails.php?id=<?=$item->getIdProduct()?>"><?=Product::getNameProductbyId($item->getIdProduct())?></a>
                                        </td>
                                        <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=$item->getQuantity()?></td>
                                        <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=number_format($price,0,',','.')?> VND</td>
                                        <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=number_format($price*$item->getQuantity(),0,',','.')?> VND</td>
                                    </tr>
                                <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?php require '../include/footer.php';?>