<?php
    require '../include/init.php';
    require '../include/header_admin.php';
    $userstotal=Account::getTotalAccount(NULL)['total'];
    $productstotal=Product::getTotalProduct()['total'];
    $ordertotal=Orders::getTotalOrder(NULL)['total'];
?>
<link rel="stylesheet" href="../Style/index_admin.css" media="screen">
<section class="u-clearfix u-section-1" id="sec-c56e"><div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-text u-text-default u-text-1"><span class="u-file-icon u-icon"><img src="images/1828791.png" alt=""></span>&nbsp;Dashboard :<br>
        </p>
        <p class="u-text u-text-default u-text-2"><span class="u-file-icon u-icon"><img src="images/731584.png" alt=""></span>&nbsp;Map:<br>
        </p>
        <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-layout-grid u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-container-style u-hover-feature u-list-item u-palette-2-base u-radius-10 u-repeater-item u-shape-round u-list-item-1" style=" cursor:pointer;"  onclick="location.href='../Admin/usermanagement.php'">
              <div class="u-container-layout u-similar-container u-container-layout-1"><span class="u-custom-item u-file-icon u-icon u-text-white u-icon-3"><img src="../Images/Website/user.png" alt=""></span>
                <h6 class="u-custom-font u-font-oswald u-text u-text-white u-text-3"><?=$userstotal?></h6>
                <h6 class="u-custom-font u-font-oswald u-hover-feature u-text u-text-default u-text-white u-text-4">Total users</h6>
              </div>
            </div>
            <div class="u-container-style u-hover-feature u-list-item u-palette-1-base u-radius-10 u-repeater-item u-shape-round u-list-item-2" style=" cursor:pointer;" onclick="location.href='../Admin/productmanagement.php'">
              <div class="u-container-layout u-similar-container u-container-layout-2"><span class="u-custom-item u-file-icon u-icon u-text-white u-icon-4"><img src="../Images/Website/product.png" alt=""></span>
                <h6 class="u-custom-font u-font-oswald u-text u-text-white u-text-5"><?=$productstotal?></h6>
                <h6 class="u-custom-font u-font-oswald u-hover-feature u-text u-text-default u-text-white u-text-6">Total products</h6>
              </div>
            </div>
            <div class="u-container-style u-hover-feature u-list-item u-palette-3-base u-radius-10 u-repeater-item u-shape-round u-list-item-3" style=" cursor:pointer;" onclick="location.href='../Admin/ordermanagement.php'">
              <div class="u-container-layout u-similar-container u-container-layout-3"><span class="u-custom-item u-file-icon u-icon u-text-white u-icon-5"><img src="../Images/Website/order.png" alt=""></span>
                <h6 class="u-custom-font u-font-oswald u-text u-text-white u-text-7"><?=$ordertotal?></h6>
                <h6 class="u-custom-font u-font-oswald u-hover-feature u-text u-text-default u-text-white u-text-8">Total orders&nbsp;</h6>
              </div>
            </div>
          </div>
        </div>
        <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-grey-light-2 u-map u-map-1">
          <div class="embed-responsive">
            <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7838.122602992265!2d106.6283628!3d10.806617000000008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1682659865701!5m2!1sen!2s" data-map="JTdCJTIycG9zaXRpb25UeXBlJTIyJTNBJTIybWFwLWVtYmVkJTIyJTJDJTIyYWRkcmVzcyUyMiUzQSUyMk1hbmhhdHRhbiUyQyUyME5ldyUyMFlvcmslMjIlMkMlMjJ6b29tJTIyJTNBMTAlMkMlMjJ0eXBlSWQlMjIlM0ElMjJyb2FkJTIyJTJDJTIybGFuZyUyMiUzQW51bGwlMkMlMjJhcGlLZXklMjIlM0FudWxsJTJDJTIybWFya2VycyUyMiUzQSU1QiU1RCUyQyUyMmVtYmVkJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZ29vZ2xlLmNvbSUyRm1hcHMlMkZlbWJlZCUzRnBiJTNEITFtMTQhMW0xMiExbTMhMWQ3ODM4LjEyMjYwMjk5MjI2NSEyZDEwNi42MjgzNjI4ITNkMTAuODA2NjE3MDAwMDAwMDA4ITJtMyExZjAhMmYwITNmMCEzbTIhMWkxMDI0ITJpNzY4ITRmMTMuMSE1ZTAhM20yITFzZW4hMnMhNHYxNjgyNjU5ODY1NzAxITVtMiExc2VuITJzJTIyJTdE"></iframe>
          </div>
        </div>
      </div></section>
    <?php require '../include/footer.php'?>