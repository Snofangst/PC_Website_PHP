<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    $user =new Account();
    $page=$_GET['page']??1;
    $search=$_GET['search']??"";
    $product_per_page=10;
    $offset=0;
    $limit=0;
    $link='?';
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(isset($_POST['ban']))
        {
            $Type=null;
            if($_POST['ban']=="Ban")
               $Type="BANNED";
            else if($_POST['ban']=="Unban")
                $Type="CUSTOMER";
            if($user->updateTypeAcc($Type,$_POST['id'])==1)
                Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Success!");
            else
                Method::getPopUpAnnouncement();
        }
        if(isset($_POST['delete']))
        {
            Method::showWarning("Do you really want to delete this account!");
            $_SESSION['idusermanagement']=$_POST['id'];
        }
        if(isset($_POST['yes'])&&isset($_SESSION['idusermanagement']))
        {
            $order=new Orders();
            $listorders=$order->selectAllOrdersByIdUser($_SESSION['idusermanagement']);
            if(!empty($listorders))
            {
                try{
                    foreach($listorders as $order)
                        $string[]="'".$order->getIdOrder()."'";
                    OrderDetails::deleteListOrderdetails(implode(',', $string));
                    Orders::deleteListOrder($_SESSION['idusermanagement']);
                }
                catch(Exception $ex)
                {
                    Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$ex);
                }
            }
            try{
                $user->deleteUser($_SESSION['idusermanagement']);
                Method::getPopUpAnnouncement('#34c759','../Images/Website/success.gif',"Successfully delete this user: ".$_SESSION['idusermanagement']);
            }
            catch(Exception $ex)
            {
                Method::getPopUpAnnouncement('#d00539','../Images/Website/fail.gif',$ex);
            }
            unset($_SESSION['idusermanagement']);
        }

    }
    $total=Account::getTotalAccount($search);
    $totalpages=ceil($total['total']/$product_per_page);
    $limit=$product_per_page;
    $offset=($page-1)*$product_per_page;
    $acclist=$user->getUsersPerPage($offset,$limit,$search);
    $location="../Admin/usermanagement.php";
    if(!empty($search))
    {
        $location.="?search=".$search;
        $link="&";
    }
    if(!empty($acclist))
    {
        if($page>$totalpages)
            header("location:".$location.$link."page=".$totalpages);
        else if ($page<=0)
            header("location:".$location.$link."page=1");
    }
?>
<link rel="stylesheet" href="../Style/UserManagement.css" media="screen">
<section class="u-align-center u-clearfix u-section-1" id="sec-6b8d">
    <div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-custom-font u-font-oswald u-text u-text-1">
            <span class="u-file-icon u-icon u-icon-1">
                <img src="../Images/Website/33308.png" alt="">
            </span>
            &nbsp;User Management
        </p>
        <div class="u-form u-form-1">
            <form method="get">
                <div class="u-clearfix u-form-horizontal u-form-spacing-15 u-inner-form" style="padding: 15px;" source="email">
                    <div class="u-form-group u-form-name u-label-none">
                        <input class="u-border-1 u-border-grey-30 u-custom-font u-font-oswald u-input u-input-rectangle u-input-1" id="name-ef64" name="search" placeholder="Search users" type="text" value="<?=$search?>"/>
                    </div>
                    <div class="u-form-group u-form-submit">
                        <a href="#" class="u-active-black u-black u-border-none u-btn u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-palette-5-base u-btn-1">
                            Find<br>
                        </a>
                        <input type="submit" value="submit" class="u-form-control-hidden">
                    </div>
                    <a style="margin-left:10px" href="../Admin/adduser.php" class="u-active-black u-black u-border-none u-btn u-btn-submit u-button-style u-custom-font u-font-oswald u-hover-palette-5-base u-btn-1">
                            Add<br>
                    </a>
                </div>
            </form>
        </div>
        <p class="u-custom-font u-font-oswald u-text u-text-default u-text-2">
            <span style="font-weight: normal;">All</span>
            (<?=$total['total']?>) | <span style="font-weight: normal;">Administrator</span>
            (<?=$total['admin']?>) | <span style="font-weight: normal;">Subscriber</span>
            (<?=$total['customer']?>)| <span style="font-weight: normal;">Guest</span>
            (<?=$total['guest']?>)
        </p>
        <div class="u-expanded-width u-table u-table-responsive u-table-1">
            <table class="u-table-entity u-table-entity-1">
                <colgroup>
                    <col width="10%">
                    <col width="25%">
                    <col width="15%">
                    <col width="20%">
                    <col width="15%">
                    <col width="15%">
                </colgroup>
                <thead class="u-black u-custom-font u-font-oswald u-table-header u-table-header-1">
                    <tr style="height: 54px;">
                        <th class="u-border-1 u-border-black u-table-cell" colspan="2">Username</th>
                        <th class="u-border-1 u-border-black u-table-cell">Email</th>
                        <th class="u-border-1 u-border-black u-table-cell">Phone number</th>
                        <th class="u-border-1 u-border-black u-table-cell">Role</th>
                        <th class="u-border-1 u-border-black u-table-cell">Total Spent</th>
                    </tr>
                </thead>
                <?php if(!empty($acclist)){foreach($acclist as $acc){ ?>
                <tbody class="u-custom-font u-font-oswald u-table-body u-table-body-1">
                    <?php if($acc->getTypeAcc()=="ADMIN"){?>
                    <tr style="height: 142px;">
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                            <img class="cell-image" src="<?=$acc->getAvatar()?>" alt=""/>
                        </td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                            <span><?=$acc->getUsername()?></span>
                        </td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?php if(!empty($acc->getEmail())) echo $acc->getEmail(); else echo 'NULL' ?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?php if(!empty($acc->getPhoneNumber())) echo $acc->getPhoneNumber(); else echo 'NULL' ?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$acc->getTypeAcc()?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=number_format($acc->getTotalSpent(),0,',','.')?> VND</td>
                    </tr>
                    <?php }else {?>
                    <tr style="height: 125px;">
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                            <img class="cell-image" src="<?=$acc->getAvatar()?>" alt=""/>
                        </td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell">
                        <span><?=$acc->getUsername()?><br/>
                            <a href="../Admin/userdetails.php?id=<?=$acc->getIdAcc()?>">Details </a>|
                            <a>
                                <form method="post" style="all:unset">
                                    <input value="<?=$acc->getIdAcc()?>" type="text" name="id" style="display:none"></input>
                                    <input style="border:unset;background-color:unset;cursor: pointer;padding:0px" value="Delete" type="submit" name="delete"></input>
                                </form>
                            </a>
                            <?php if($acc->getTypeAcc()=='CUSTOMER') {?>
                                |<a>
                                    <form method="post" style="all:unset">
                                        <input value="<?=$acc->getIdAcc()?>" type="text" name="id" style="display:none"></input>
                                        <input style="border:unset;background-color:unset;cursor: pointer;padding:0px" value="Ban" type="submit" name="ban"></input>
                                    </form>
                                </a>
                            <?php } else if($acc->getTypeAcc()=='BANNED'){ ?>
                                |<a>
                                    <form method="post" style="all:unset">
                                        <input value="<?=$acc->getIdAcc()?>" type="text" name="id" style="display:none"></input>
                                        <input style="border:unset;background-color:unset;cursor: pointer;padding:0px" value="Unban" type="submit" name="ban"></input>
                                    </form>
                                </a>
                            <?php } ?>
                        </span>
                        </td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?php if(!empty($acc->getEmail())) echo $acc->getEmail(); else echo 'NULL' ?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?php if(!empty($acc->getPhoneNumber())) echo $acc->getPhoneNumber(); else echo 'NULL' ?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=$acc->getTypeAcc()?></td>
                        <td class="u-border-2 u-border-grey-30 u-border-no-left u-border-no-right u-table-cell"><?=number_format($acc->getTotalSpent(),0,',','.')?> VND</td>
                    </tr>
                    <?php }?>
                </tbody>
                <?php }}?>
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
<?php 
    require '../include/footer.php';
?>