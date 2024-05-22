<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    $order =new Orders();
    $page=$_GET['page']??1;
    $sort=$_GET['sort']??"";
    $product_per_page=10;
    $offset=0;
    $limit=0;
    $link='?';
    if(isset($_POST['yes'])&&isset($_SESSION['idordermanagement']))
    {
        $order=new Orders();
        if(!empty($_SESSION['idordermanagement']))
        {
            try{
                OrderDetails::deleteListOrderdetails("'".$_SESSION['idordermanagement']."'");
                Orders::deleteSpecificOrder($_SESSION['idordermanagement']);
                Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Successfully delete this order: ".$_SESSION['idordermanagement']);
            }
            catch(Exception $ex)
            {
                Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$ex);
            }
        }
        unset($_SESSION['idordermanagement']);
    }
    $total=Orders::getTotalOrder($sort);
    $totalpages=ceil($total['total']/$product_per_page);
    $limit=$product_per_page;
    $offset=($page-1)*$product_per_page;
    $orderlist=$order->getOrdersPerPage($offset,$limit,$sort);
    $location="../Admin/ordermanagement.php";
    if(!empty($sort))
    {
        $location.="?sort=".$sort;
        $link="&";
    }
    if($page>$totalpages)
        header("location:".$location.$link."page=".$totalpages);
    else if ($page<=0)
        header("location:".$location.$link."page=1");
    if(isset($_POST['delete']))
    {
        Method::showWarning("Do you really want to delete this account!");
        $_SESSION['idordermanagement']=$_POST['id'];
    }
    
?>
<link rel="stylesheet" href="../Style/OrderManagement.css" media="screen">
<section class="u-align-center u-clearfix u-white u-section-1" id="carousel_fe34">
<div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-custom-font u-font-oswald u-text u-text-1">ORDER MANAGEMENT</h1>
    <div class="u-expanded-width u-table u-table-responsive u-table-1">
        <div style="float:left;margin-bottom:10px">
            <form enctype="multipart/form-data" method="get">
                <select id="sort" name="sort" onchange="this.form.submit();">
                    <option  value="">SORT</option>
                    <option <?php if($sort=="totalasc") echo "selected";?> value="totalasc">Ascending Total</option>
                    <option <?php if($sort=="totaldesc") echo "selected";?> value="totaldesc">Descending Total</option>
                    <option <?php if($sort=="dateasc") echo "selected";?> value="dateasc">Ascending Date</option>
                    <option <?php if($sort=="datedesc") echo "selected";?> value="datedesc">Descending Date</option>
                </select>
            </form>
        </div>
        <table class="u-table-entity">
            <colgroup>
                <col width="20%">
                <col width="20%">
                <col width="16.7%">
                <col width="23.3%">
                <col width="20%">
            </colgroup>
            <thead class="u-align-center u-black u-custom-font u-font-oswald u-table-header u-table-header-1">
                <tr style="height: 51px;">
                    <th class="u-border-1 u-border-black u-table-cell">ORDER ID</th>
                    <th class="u-border-1 u-border-black u-table-cell">ACCOUNT ID</th>
                    <th class="u-border-1 u-border-black u-table-cell">TOTAL</th>
                    <th class="u-border-1 u-border-black u-table-cell">DATE</th>
                    <th class="u-border-1 u-border-black u-table-cell">FUNCTIONS</th>
                </tr>
            </thead>
            <tbody class="u-align-center u-custom-font u-font-oswald u-table-body u-table-body-1">
                <?php if(!empty($orderlist)){ foreach($orderlist as $order){ ?>
                <tr style="height: 82px;">
                    <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$order->getIdOrder()?></td>
                    <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$order->getIdAcc()?></td>
                    <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=number_format($order->getTotal(),0,',','.')?> VND</td>
                    <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?php echo date('d-m-Y H:i:s', strtotime($order->getDate()));?></td>
                    <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                        <a href="../Admin/orderdetails.php?id=<?=$order->getIdOrder()?>">DETAILS</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a>
                            <form method="post" style="all:unset">
                                <input value="<?=$order->getIdAcc()?>" type="text" name="id" style="display:none"></input>
                                <input value="<?=$order->getIdOrder()?>" type="text" name="id" style="display:none"></input>
                                <input style="border:unset;background-color:unset;cursor: pointer;padding:0px" value="DELETE" type="submit" name="delete"></input>
                            </form>
                        </a>
                    </td>
                </tr>
                <?php }}?>
            </tbody>
        </table>
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