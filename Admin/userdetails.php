<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    if(isset($_GET['id']))
    {
        $acc=new Account();
        $order=new Orders();
        $acc=$acc->getUserById($_GET['id']);
        if(empty($acc))
            header("location:../Admin/Error.php");
        $orderlist=$order->selectAllOrdersByIdUser($_GET['id']);
    }
    else
        header("location:../Admin/Error.php");
?>
<link rel="stylesheet" href="../Style/UserDetails.css" media="screen">
<section class="u-align-center u-clearfix u-grey-15 u-section-1" id="sec-03a4">
    <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-expanded-width u-tab-links-align-justify u-tabs u-tabs-1">
            <ul class="u-tab-list u-unstyled" role="tablist">
                <li class="u-tab-item" role="presentation">
                    <a class="active u-active-white u-button-style u-custom-font u-font-oswald u-tab-link u-tab-link-1" id="link-tab-14b7" href="#tab-14b7" role="tab" aria-controls="tab-14b7" aria-selected="true">CUSTOMER INFORMATION</a>
                </li>
                <li class="u-tab-item" role="presentation">
                    <a class="u-active-white u-button-style u-custom-font u-font-oswald u-tab-link u-tab-link-2" id="link-tab-0da5" href="#tab-0da5" role="tab" aria-controls="tab-0da5" aria-selected="false">CUSTOMER INVOICES</a>
                </li>
            </ul>
            <div class="u-tab-content">
                <div class="u-align-left u-container-style u-tab-active u-tab-pane u-white u-tab-pane-1" id="tab-14b7" role="tabpanel" aria-labelledby="link-tab-14b7">
                    <div class="u-container-layout u-container-layout-1">
                        <a href="../Admin/changeuserinfo.php?id=<?=$acc->getIdAcc()?>" style="left:85%;position:absolute" class="u-active-black u-black u-border-none u-btn u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-palette-5-base u-btn-1">
                                Update<br>
                        </a>
                        <p class="u-custom-font u-font-oswald u-text u-text-1">
                            ACCOUNT ID: <span style="color:red"><?=$acc->getIdAcc()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-2">
                            USERNAME: <span style="color:red"><?=$acc->getUsername()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-3">
                            RECIPIENT NAME: <span style="color:red"><?=$acc->getReceiverName()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-4">
                            PHONE NUMBER: <span style="color:red"><?=$acc->getPhoneNumber()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-5">
                            EMAIL: <span style="color:red"><?=$acc->getEmail()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-6">
                            TYPE: <span style="color:red"><?=$acc->getTypeAcc()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-7">
                            GENDER: <span style="color:red"><?=$acc->getGender()?></span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-8">
                            ADDRESS: <span style="color:red"><?=$acc->getAddress()?></span>
                        </p>
                        <div class="u-border-3 u-border-grey-dark-1 u-expanded-width-lg u-expanded-width-md u-expanded-width-xl u-line u-line-horizontal u-line-1"></div>
                        <p class="u-custom-font u-font-oswald u-text u-text-9">
                            TOTAL SPENT: <span style="color:red"><?=number_format($acc->getTotalSpent(),0,',','.')?> VND</span>
                        </p>
                        <p class="u-custom-font u-font-oswald u-text u-text-10">
                            NUMBER OF PURCHASED: <span style="color:red"><?php if(intval($acc->getNumberOfPurchased())<=1) echo $acc->getNumberOfPurchased().' item'; else echo $acc->getNumberOfPurchased().' items';  ?> </span>
                        </p>
                    
                    </div>
                </div>
                <div class="u-container-style u-tab-pane u-white u-tab-pane-2" id="tab-0da5" role="tabpanel" aria-labelledby="link-tab-0da5">
                    <div class="u-container-layout u-valign-top-lg u-valign-top-md u-valign-top-xl u-container-layout-2">
                        <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-table u-table-responsive u-table-1">
                            <table class="u-table-entity">
                                <colgroup>
                                    <col width="15.2%">
                                    <col width="23.6%">
                                    <col width="23.1%">
                                    <col width="18.1%">
                                    <col width="20%">
                                </colgroup>
                                <thead class="u-align-left u-custom-font u-font-oswald u-palette-5-dark-2 u-table-header u-table-header-1">
                                    <tr style="height: 52px;">
                                        <th class="u-table-cell">ORDER ID</th>
                                        <th class="u-table-cell">DATE</th>
                                        <th class="u-table-cell">PAYMENT METHOD</th>
                                        <th class="u-table-cell">DELIVERY STATUS</th>
                                        <th class="u-table-cell">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="u-align-left u-custom-font u-font-oswald u-table-body u-table-body-1">
                                    
                                        <?php if(!empty($orderlist)) {
                                            foreach($orderlist as $order){ 
                                                if($order->getDelivery()!='IN PROGRESS'){?>
                                                <tr style="height: 60px;">
                                                    <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-first-column u-table-cell">
                                                        <a href="../Admin/orderdetails.php?id=<?=$order->getIdOrder() ?>"><?=$order->getIdOrder() ?></a>
                                                    </td>
                                                    <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?php echo date('d-m-Y H:i:s', strtotime($order->getDate()));?></td>
                                                    <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=$order->getPayment() ?></td>
                                                    <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=$order->getDelivery() ?></td>
                                                    <td class="u-border-1 u-border-grey-15 u-border-no-left u-border-no-right u-table-cell"><?=number_format($order->getTotal(),0,',','.')?> VND</td>
                                                </tr>
                                        <?php }}} ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require '../include/footer.php'
?>