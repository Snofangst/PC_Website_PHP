<?php
    Class Method{
        public static function findItemInCookie($array,$id)
        {
            foreach($array as $key=>$item)
            {
                if($item->getIdProduct()==$id)
                    return $key;
            }
            return null;
        }
        public static function getSuccessPopUp()
        {
            echo'
                <section class="u-align-center u-clearfix u-container-style u-dialog-block u-dialog-section-6" id="carousel_2d64" data-dialog-show-on="timer" data-dialog-show-interval="0" >
                    <div class="u-align-center u-container-style u-dialog u-image u-shape-rectangle u-image-1" data-image-width="539" data-image-height="356">
                        <div class="u-container-layout u-container-layout-1"></div><button class="u-dialog-close-button u-file-icon u-icon u-text-white u-icon-1"><img src="../Images/Website/white-exit.png" alt=""></button>
                    </div>
                </section>
                <style>
                    .u-dialog-section-6 .u-image-1 {
                        width: 500px;
                        min-height: 300px;
                        height: auto;
                        background-image: url("../Images/Website/FRAME2.png");
                        background-position: 50% 50%;
                        margin: 250px auto 0;
                    }

                    .u-dialog-section-6 .u-container-layout-1 {
                        padding: 0 0 25px;
                    }

                    .u-dialog-section-6 .u-icon-1 {
                        width: 20px;
                        height: 20px;
                        left: auto;
                        top: 25px;
                        position: absolute;
                        background-image: none;
                        right: 30px;
                        padding: 2px;
                    }

                    @media (max-width: 767px) {
                        .u-dialog-section-6 .u-image-1 {
                            width: 300px;
                            min-height: 200px;
                        }
                        .u-dialog-section-6 .u-icon-1 {
                            width: 15px;
                            height: 15px;
                            top: 23px;
                            right: 17px;
                        }
                    }

                    @media (max-width: 575px) {
                        .u-dialog-section-6 .u-image-1 {
                            width: 300px;
                            min-height: 200px;
                        }
                        .u-dialog-section-6 .u-icon-1 {
                            width: 15px;
                            height: 15px;
                            top: 23px;
                            right: 17px;
                        }
                    }
                </style>
            ';
        }
        public static function showWarning(?string $message="Are you sure")
        {
            echo'<section class="u-align-center u-black u-clearfix u-container-style u-dialog-block u-opacity u-opacity-20 u-dialog-section-5" id="carousel_b733" data-dialog-show-on="timer" data-dialog-show-interval="0" data-dialog-show-on-list="1829803284">
                    <div class="u-align-center u-border-3 u-border-palette-3-base u-container-style u-dialog u-radius-10 u-shape-round u-white u-dialog-1">
                        <div class="u-container-layout u-container-layout-1">
                            <img class="u-image u-image-contain u-image-default u-preserve-proportions u-image-1" src="../Images/Website/warning.gif" alt="" data-image-width="150" data-image-height="150">
                            <h5 class="u-custom-font u-font-oswald u-text u-text-black u-text-1">'.$message.'</h5>
                            <form method="post">
                            <div>
                                <input type="submit" value="YES" name="yes" class="u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-oswald u-palette-2-base u-radius-10 u-btn-1">
                            </div>
                            <div>
                                <input type="submit" value="NO" name="no" class="u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-oswald u-palette-4-base u-radius-10 u-btn-2">
                            </div>
                        </form>
                        </div>
                        <button class="u-dialog-close-button u-icon u-text-black u-icon-1">
                            <svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 413.348 413.348">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-5801"></use>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xml:space="preserve" class="u-svg-content" viewBox="0 0 413.348 413.348" id="svg-5801">
                                <path d="m413.348 24.354-24.354-24.354-182.32 182.32-182.32-182.32-24.354 24.354 182.32 182.32-182.32 182.32 24.354 24.354 182.32-182.32 182.32 182.32 24.354-24.354-182.32-182.32z"></path>
                            </svg>
                        </button>
                    </div>
                    </section><style>.u-dialog-section-5 .u-dialog-1 {
                        width: 422px;
                        min-height: 289px;
                        height: auto;
                        background-image: none;
                        margin: 112px auto 60px calc(((100% - 1140px) / 2) + 330px);
                        }

                        .u-dialog-section-5 .u-container-layout-1 {
                        padding: 9px 30px;
                        }

                        .u-dialog-section-5 .u-image-1 {
                        width: 112px;
                        height: 112px;
                        margin: 9px auto 0;
                        }

                        .u-dialog-section-5 .u-text-1 {
                        font-size: 1.5rem;
                        font-weight: 700;
                        margin: 0 9px;
                        }

                        .u-dialog-section-5 .u-btn-1 {
                        background-image: none;
                        font-weight: 700;
                        margin: 17px auto 0 60px;
                        padding: 11px 31px 12px 30px;
                        }

                        .u-dialog-section-5 .u-btn-2 {
                        background-image: none;
                        font-weight: 700;
                        margin: -49px 74px 0 auto;
                        padding: 11px 31px 12px 30px;
                        }

                        .u-dialog-section-5 .u-icon-1 {
                        width: 23px;
                        height: 23px;
                        left: auto;
                        top: 17px;
                        position: absolute;
                        right: 16px;
                        }

                        @media (max-width: 1199px) {
                        .u-dialog-section-5 .u-dialog-1 {
                            width: 383px;
                            background-position: 50% 50%;
                            min-height: 266px;
                            margin-left: auto;
                        }

                        .u-dialog-section-5 .u-image-1 {
                            width: 112px;
                            height: 112px;
                            margin-top: 0;
                        }

                        .u-dialog-section-5 .u-text-1 {
                            width: auto;
                            margin-left: 9px;
                            margin-right: 9px;
                        }

                        .u-dialog-section-5 .u-btn-1 {
                            margin-top: 17px;
                            margin-left: 51px;
                        }

                        .u-dialog-section-5 .u-btn-2 {
                            margin-top: -49px;
                            margin-right: 51px;
                        }
                        }

                        @media (max-width: 991px) {
                        .u-dialog-section-5 .u-dialog-1 {
                            width: 383px;
                            min-height: 214px;
                        }

                        .u-dialog-section-5 .u-container-layout-1 {
                            padding-top: 8px;
                            padding-bottom: 8px;
                        }

                        .u-dialog-section-5 .u-image-1 {
                            width: 86px;
                            height: 86px;
                        }

                        .u-dialog-section-5 .u-text-1 {
                            font-size: 1.25rem;
                        }

                        .u-dialog-section-5 .u-btn-1 {
                            margin-top: 9px;
                            margin-left: 74px;
                            padding: 7px 22px 8px;
                        }

                        .u-dialog-section-5 .u-btn-2 {
                            margin-top: -41px;
                            margin-right: 80px;
                            padding: 7px 23px 8px 22px;
                        }
                        }

                        @media (max-width: 767px) {
                        .u-dialog-section-5 .u-dialog-1 {
                            width: 343px;
                            min-height: 205px;
                        }

                        .u-dialog-section-5 .u-container-layout-1 {
                            padding-top: 0;
                            padding-right: 25px;
                            padding-bottom: 0;
                        }

                        .u-dialog-section-5 .u-image-1 {
                            width: 86px;
                            height: 86px;
                        }

                        .u-dialog-section-5 .u-text-1 {
                            margin-top: -3px;
                        }

                        .u-dialog-section-5 .u-btn-1 {
                            margin-top: 11px;
                            margin-left: 56px;
                            padding-right: 22px;
                            padding-bottom: 8px;
                        }

                        .u-dialog-section-5 .u-btn-2 {
                            margin-top: -41px;
                            margin-right: 56px;
                        }
                        }

                        @media (max-width: 575px) {
                        .u-dialog-section-5 .u-dialog-1 {
                            width: 340px;
                            background-position: 45.33% 50%;
                            min-height: 222px;
                            margin-top: 167px;
                        }

                        .u-dialog-section-5 .u-container-layout-1 {
                            padding-top: 9px;
                            padding-bottom: 9px;
                            padding-left: 25px;
                        }

                        .u-dialog-section-5 .u-image-1 {
                            margin-top: 0;
                        }

                        .u-dialog-section-5 .u-text-1 {
                            margin-top: 0;
                            margin-left: 0;
                            margin-right: 0;
                        }

                        .u-dialog-section-5 .u-btn-1 {
                            margin-top: 12px;
                            margin-left: 58px;
                        }

                        .u-dialog-section-5 .u-btn-2 {
                            margin-top: -40px;
                            margin-right: 58px;
                        }
                        }</style>';
        }
        public static function getCurrentUrlWithParameter($url,?string $search=null,?string $catalog=null,?string $brand=null)
        {
            $count=0;
            if(!empty($search))
            {
                $url.='?search='.$search;
                $count++;
            }
            if(!empty($catalog))
            {
                if($count>0)
                    $url.='&';
                else
                    $url.='?';
                $url.='catalog='.$catalog;
                $count++;
            }
        
            if(!empty($brand))
            {
                if($count>0)
                    $url.='&';
                else
                    $url.='?';
                $url.='brand='.$brand;
            }
            return $url;
        }
        public static function getRandomPassword(?int $length=20)
        {
            if($length<10)
                $length=10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomPassword = '';
            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomPassword .= $characters[$index];
            }
            return $randomPassword;
        }
        public static function getBestSellerProduct(?int $limit=5)
        {
            try{
                if($limit<0)
                    $limit=1;
                $sql="SELECT DISTINCT product.IdProduct, sum(orderdetails.Quantity), nameproduct,`table`.FileImage FROM `orderdetails`,`orders`, `product` left join ( select imagedetails.IdProduct, image.FileImage from image,imagedetails where imagedetails.IdImage=image.IdImage and image.FileImage LIKE '%Slide0%') as `table` on `table`.IdProduct=product.IdProduct where product.IdProduct=orderdetails.IdProduct and `table`.FileImage is not null and orderdetails.IdOrder=orders.IdOrder and orders.Delivery='CONFIRMED' GROUP BY IdProduct,nameproduct,fileimage ORDER BY sum(orderdetails.Quantity) DESC LIMIT :limit;";
                $stmt=Database::$pdo->prepare($sql);
                $stmt->bindParam(':limit',$limit,PDO::PARAM_STR);
                $stmt->execute();
                $bestseller=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($bestseller))
                    return $bestseller;
                return null;

            }catch(PDOException $ex)
            {
                echo '<script>alert("'.$ex->getMessage().'")</script>';
                return null;
            }

        }
        public static function random_slogan()
        {
            $sloganlist=array("That's Handy, Harry!","Stick It In The Computer.","Computer Is The Buzz.","Enormous, Madness and Reliable.","Building The Future.","Computer's Like Heaven.","From Nonhuman To Hominal.","Have A Break. Have A Computer.","Computer - The Revolution.","Work Hard, Assisted Harder.","You Need A Computer.","Go To Work On A Computer.","Single Computer, We Care","See The Computer, Feel The Shine.","Hybrid Computers Are What We Do.","Enhance your computing experience","User-friendly and stylish","2 Computers Are Better Than 1.","Computer Built To Perfection.");
            return $slogan=$sloganlist[array_rand($sloganlist,1)];
        }
        public static function getPopUpAnnouncement(?string $color='#d00539',?string $gif='../Images/Website/fail.gif',$message='Oops! Something went wrong!')
        {
            echo'
                <section class="u-align-center u-black u-clearfix u-container-style u-dialog-block u-opacity u-opacity-0 u-dialog-section-4" id="carousel_5c64" data-dialog-show-on="timer" data-dialog-show-interval="0" >
                    <div class="u-align-center u-border-4 u-border-grey-75 u-container-style u-dialog u-radius-10 u-shape-round u-white u-dialog-1" style="border-color:'.$color.'">
                    <div class="u-container-layout u-container-layout-1">
                        <img class="u-image u-image-contain u-image-default u-preserve-proportions u-image-1" src="'.$gif.'" alt="" data-image-width="640" data-image-height="640">
                        <p class="u-custom-font u-font-oswald u-text u-text-1" style="color:'.$color.'">'.$message.'</p>
                    </div><button class="u-dialog-close-button u-file-icon u-icon u-text-black u-icon-1"><img src="../Images/Website/exit.png" alt=""></button>
                    </div>
                </section>
                <style>
                    .u-dialog-section-4 .u-dialog-1 {
                        background-position: 45.33% 50%;
                        background-image: none;
                        height: auto;
                        min-height: 253px;
                        width: 476px;
                        box-shadow: 5px 5px 20px 0 rgba(0,0,0,0.4);
                        margin: 200px auto;
                    }
                
                    .u-dialog-section-4 .u-container-layout-1 {
                    padding: 0 0 30px;
                    }
                
                    .u-dialog-section-4 .u-image-1 {
                    width: 140px;
                    height: 140px;
                    margin: 13px auto 0;
                    }
                
                    .u-dialog-section-4 .u-text-1 {
                    font-size: 1.875rem;
                    width: 400px;
                    margin: 0 auto;
                    }
                
                    .u-dialog-section-4 .u-icon-1 {
                    width: 22px;
                    height: 22px;
                    left: auto;
                    top: 18px;
                    position: absolute;
                    right: 17px;
                    padding: 0;
                    }
                
                    @media (max-width: 1199px) {
                    .u-dialog-section-4 .u-dialog-1 {
                        background-position: 50% 50%;
                        width: 367px;
                    }
                
                    .u-dialog-section-4 .u-image-1 {
                        width: 140px;
                        height: 140px;
                    }
                
                    .u-dialog-section-4 .u-text-1 {
                        width: 310px;
                    }
                
                    .u-dialog-section-4 .u-icon-1 {
                        right: 13px;
                    }}
                
                    @media (max-width: 991px) {
                    .u-dialog-section-4 .u-dialog-1 {
                        width: 358px;
                    }
                
                    .u-dialog-section-4 .u-icon-1 {
                        right: 23px;
                    }}
                
                    @media (max-width: 767px) {
                    .u-dialog-section-4 .u-dialog-1 {
                        width: 290px;
                        min-height: 194px;
                    }
                
                    .u-dialog-section-4 .u-container-layout-1 {
                        padding-bottom: 18px;
                    }
                
                    .u-dialog-section-4 .u-image-1 {
                        width: 100px;
                        height: 100px;
                        margin-top: 22px;
                    }
                
                    .u-dialog-section-4 .u-text-1 {
                        font-size: 1.25rem;
                        width: 222px;
                        margin-top: 7px;
                    }
                
                    .u-dialog-section-4 .u-icon-1 {
                        top: 10px;
                        right: 11px;
                    }}
                
                    @media (max-width: 575px) {
                    .u-dialog-section-4 .u-dialog-1 {
                        background-position: 45.33% 50%;
                        width: 290px;
                    }
                
                    .u-dialog-section-4 .u-container-layout-1 {
                        padding-bottom: 15px;
                    }
                
                    .u-dialog-section-4 .u-image-1 {
                        margin-top: 14px;
                    }
                
                    .u-dialog-section-4 .u-text-1 {
                        width: 222px;
                    }
                
                    .u-dialog-section-4 .u-icon-1 {
                        width: 16px;
                        height: 16px;
                        top: 18px;
                        right: 17px;
                    }}
                </style>
            ';
        }
    }
?>