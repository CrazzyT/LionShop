<?php
/* @var $this yii\web\View */
?>
<section id="cart-page">
        <div class="container">
            <!-- ========================================= CONTENT ========================================= -->
            <div class="col-xs-12 col-md-9 items-holder no-margin">
                <?php if (is_array($cartLists)):foreach ($cartLists as $v):?>
                <div class="row no-margin cart-item">
                    <div class="col-xs-12 col-sm-2 no-margin">
                        <a href="#" class="thumb-holder">
                            <img class="lazy" alt="" src="<?=$v['catbest'];?>" />
                        </a>
                    </div>

                    <div class="col-xs-12 col-sm-5 ">
                        <div class="title">
                            <a href="<?= $v['url'];?>"><?= $v['goods_name'];?></a>
                        </div>
                        <div class="brand"><?= $v['brand_name'];?></div>
                    </div>

                    <div class="col-xs-12 col-sm-3 no-margin">
                        <div class="quantity">
                            <div class="le-quantity">
                                <form>
                                    <a class="minus" href="#reduce"></a>
                                    <input name="quantity" readonly="readonly" type="text" value="<?=$v['buy_number']?>" />
                                    <a class="plus" href="#add"></a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2 no-margin">

                        <div class="price">
                            ￥<?= $v['goods_price'];?>
                        </div>
                        <a class="close-btn" href="#"></a>
                    </div>
                </div><!-- /.cart-item -->
                <?php endforeach;endif;?>
            </div>
            <!-- ========================================= CONTENT : END ========================================= -->

            <!-- ========================================= SIDEBAR ========================================= -->

            <div class="col-xs-12 col-md-3 no-margin sidebar ">
                <div class="widget cart-summary">
                    <h1 class="border">商品购物车</h1>
                    <div class="body">
                        <ul class="tabled-data no-border inverse-bold">
                            <li>
                                <label>商品总金额</label>
                                <div class="value pull-right">￥<?=$price;?>.00</div>
                            </li>
                            <li>
                                <label>配送费</label>
                                <div class="value pull-right">全国包邮</div>
                            </li>
                        </ul>
                        <ul id="total-price" class="tabled-data inverse-bold no-border">
                            <li>
                                <label>订单总金额</label>
                                <div class="value pull-right">￥<?=$price;?>.00</div>
                            </li>
                        </ul>
                        <div class="buttons-holder">
                            <a class="le-button big" href="http://localhost/~ibrahim/themeforest/HTML/mediacenter/upload/PHP/checkout" >去结算</a>
                            <a class="simple-link block" href="http://localhost/~ibrahim/themeforest/HTML/mediacenter/upload/PHP/home" >继续购买</a>
                        </div>
                    </div>
                </div><!-- /.widget -->
                <div id="cupon-widget" class="widget">
                    <h1 class="border">优惠券</h1>
                    <div class="body">
                        <form>
                            <div class="inline-input">
                                <input data-placeholder="enter coupon code" type="text" />
                                <button class="le-button" type="submit">使用</button>
                            </div>
                        </form>
                    </div>
                </div><!-- /.widget -->
            </div><!-- /.sidebar -->

            <!-- ========================================= SIDEBAR : END ========================================= -->
        </div>
</section>