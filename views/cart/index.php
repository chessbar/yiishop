<!-- ============================================================= HEADER : END ============================================================= -->		<section id="cart-page">
    <?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    ?>
    <div class="container">
        <!-- ========================================= CONTENT ========================================= -->
        <?php
            $form = ActiveForm::begin([
                'action'=>yii\helpers\Url::to(['order/add']),
            ])
        ?>
        <div class="col-xs-12 col-md-9 items-holder no-margin">

            <?php
            $total =0;
            $freight =0;
            if(!empty($cart)):
                foreach ($cart as $k=>$pro):
                    $total += $pro->productnum*$pro->price;
                    $freight = 10;
                    ?>
                    <input type="hidden" name="OrderDetail[<?php echo $k;?>][productid]" value="<?php echo $pro->productid;?>">
                    <input type="hidden" name="OrderDetail[<?php echo $k;?>][productname]" value="<?php echo $pro->product->title;?>">
                    <input type="hidden" name="OrderDetail[<?php echo $k;?>][price]" value="<?php echo $pro->price;?>">

            <div class="row no-margin cart-item">
                <div class="col-xs-12 col-sm-2 no-margin">
                    <a href="<?php echo yii\helpers\Url::to(['product/detail','productid'=>$pro->productid]);?>" class="thumb-holder">
                        <img class="lazy" alt="" src="<?php echo $pro->product->cover;?>-coversmall" />
                    </a>
                </div>

                <div class="col-xs-12 col-sm-5 ">
                    <div class="title">
                        <a href="<?php echo yii\helpers\Url::to(['product/detail','productid'=>$pro->productid]);?>"><?php echo $pro->product->title;?></a>
                    </div>
                    <div class="brand">sony</div>
                </div> 

                <div class="col-xs-12 col-sm-3 no-margin">
                    <div class="quantity">
                        <div class="le-quantity">
                                <a class="minus" href="#reduce"></a>
                                <input type="text" readonly="readonly" id="<?php echo $pro->cartid;?>" name="OrderDetail[<?php echo $k;?>][productnum]" value="<?php echo $pro->productnum;?>">
                                <a class="plus" href="#add"></a>
                        </div>
                    </div>
                </div> 

                <div class="col-xs-12 col-sm-2 no-margin">
                    <div class="price">
                        $<span><?php echo $pro->price;?></span>
                    </div>
                    <a class="close-btn" href="<?php echo yii\helpers\Url::to(['cart/del','cartid'=>$pro->cartid]);?>"></a>
                </div>
            </div><!-- /.cart-item -->
                    <?php endforeach;?>
                <?php else:?>
                购物车内暂无商品!
            <?php endif;?>
        </div>
        <!-- ========================================= CONTENT : END ========================================= -->

        <!-- ========================================= SIDEBAR ========================================= -->

        <div class="col-xs-12 col-md-3 no-margin sidebar ">
            <div class="widget cart-summary">
                <h1 class="border">商品购物车</h1>
                <div class="body">
                    <ul class="tabled-data no-border inverse-bold">
                        <li>
                            <label>购物车总价</label>
                            <div class="value pull-right total-price">$ <span><?php echo $total;?></span></div>
                        </li>
                        <li>
                            <label>运费</label>
                            <div class="value pull-right freight">$ <span><?php echo $freight;?></span></div>
                        </li>
                    </ul>
                    <ul id="total-price" class="tabled-data inverse-bold no-border">
                        <li>
                            <label>订单总价</label>
                            <div class="value pull-right order-price">$<span><?php echo $total+$freight;?></span></div>
                        </li>
                    </ul>
                    <div class="buttons-holder">
                        <?php echo Html::submitButton('去结算',['class'=>'le-button big']);?>
                        <a class="simple-link block" href="index.html" >继续购物</a>
                    </div>
                </div>
            </div><!-- /.widget -->

            <div id="cupon-widget" class="widget">
                <h1 class="border">使用优惠券</h1>
                <div class="body">
                        <div class="inline-input">
                            <input data-placeholder="请输入优惠券码" type="text" />
                            <button class="le-button" type="submit">使用</button>
                        </div>
                </div>
            </div><!-- /.widget -->
        </div><!-- /.sidebar -->

        <!-- ========================================= SIDEBAR : END ========================================= -->
    </div>
    <?php ActiveForm::end();?>
</section>		<!-- ============================================================= FOOTER ============================================================= -->