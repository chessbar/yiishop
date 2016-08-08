<!-- ============================================================= HEADER : END ============================================================= -->		<!-- ========================================= CONTENT ========================================= -->
<?php
use yii\bootstrap\ActiveForm;
?>
<section id="checkout-page">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'action'=>yii\helpers\Url::to(['order/confirm']),
                ])
                ?>
        <input type="hidden" name="orderid" value="<?php echo Yii::$app->request->get('orderid');?>">
        <div class="col-xs-12 no-margin">
            <section id="shipping-address" style="margin-bottom:100px;margin-top:-10px">
                <h2 class="border h1">收货地址</h2>
                    <a href="#" id="createlink">新建联系人</a>
                    <?php foreach($addresses as $address):?>
                    <div class="row field-row" style="margin-top:10px">
                        <div class="col-xs-12">
                            <input  class="le-radio big" type="radio" name="addressid" value="<?php echo $address['addressid'];?>"/>
                            <a class="simple-link bold" href="#"><?php echo $address['firstname'].$address['lastname']." ".$address['address']." ".$address['telphone'];?></a>
                        </div>
                        <a href="<?php echo yii\helpers\Url::to(['address/del','addressid'=>$address['addressid']]);?>">删除</a>
                    </div><!-- /.field-row -->
                    <?php endforeach;?>
            </section><!-- /#shipping-address -->

            <div class="billing-address" style="display:none;">
                <h2 class="border h1">新建联系人</h2>
                <?php $form = ActiveForm::begin([
                    'action'=>yii\helpers\Url::to(['address/add']),
                ]);?>
                    <div class="row field-row">
                        <div class="col-xs-12 col-sm-6">
                            <label>姓*</label>
                            <input class="le-input" name="firstname">
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>名*</label>
                            <input class="le-input" name="lastname">
                        </div>
                    </div><!-- /.field-row -->

                    <div class="row field-row">
                        <div class="col-xs-12">
                            <label>公司名称</label>
                            <input class="le-input" name="company">
                        </div>
                    </div><!-- /.field-row -->

                    <div class="row field-row">
                        <div class="col-xs-12 col-sm-6">
                            <label>地址*</label>
                            <input class="le-input" data-placeholder="例如：北京市朝阳区" name="address1">
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <label>&nbsp;</label>
                            <input class="le-input" data-placeholder="例如：酒仙桥北路" name="address2">
                        </div>
                    </div><!-- /.field-row -->

                    <div class="row field-row">
                        <div class="col-xs-12 col-sm-4">
                            <label>邮编</label>
                            <input class="le-input"  name="postcode">
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <label>电子邮箱地址*</label>
                            <input class="le-input" name="email">
                        </div>

                        <div class="col-xs-12 col-sm-4">
                            <label>联系电话*</label>
                            <input class="le-input" name="telphone">
                        </div>
                    </div><!-- /.field-row -->

                    <!--<div class="row field-row">
                        <div id="create-account" class="col-xs-12">
                            <input  class="le-checkbox big" type="checkbox"  />
                            <a class="simple-link bold" href="#">新建联系人？</a>
                        </div>
                    </div>--><!-- /.field-row -->

                    <div class="place-order-button">
                        <button class="le-button small">新建</button>
                    </div><!-- /.place-order-button -->
                <?php ActiveForm::end();?>
            </div><!-- /.billing-address -->


            <section id="your-order">
                <h2 class="border h1">您的订单详情</h2>
                    <?php
                    $total = 0;
                    foreach($details as $detail):
                        $total += $detail['productnum'] * $detail['price'];
                        ?>

                    <div class="row no-margin order-item">
                        <div class="col-xs-12 col-sm-1 no-margin">
                            <a href="#" class="qty"><?php echo $detail['productnum'];?> x</a>
                        </div>

                        <div class="col-xs-12 col-sm-9 ">
                            <div class="title"><a href="<?php echo yii\helpers\Url::to(['product/detail','productid'=>$detail['productid']]);?>" target="_blank"><?php echo $detail['productname'];?> </a></div>
                            <div class="brand">sony</div>
                        </div>

                        <div class="col-xs-12 col-sm-2 no-margin">
                            <div class="price">$<?php echo $detail['price'];?></div>
                        </div>
                    </div>
                    <?php endforeach;?>

            </section><!-- /#your-order -->

            <div id="total-area" class="row no-margin">
                <div class="col-xs-12 col-lg-4 col-lg-offset-8 no-margin-right">
                    <div id="subtotal-holder">
                        <ul class="tabled-data inverse-bold no-border">
                            <li>
                                <label>商品总价</label>
                                <div style="width:100%;text-align:right" class="value ">$<?php echo $total;?></div>
                            </li>
                            <li>
                                <label>选择快递</label>
                                <div style="width:100%;text-align:right" class="value">
                                    <div class="radio-group">
                                        <?php foreach($express as $key=>$exp):
                                            ?>
                                        <input class="le-radio" type="radio" name="expressid" value="<?php echo $key;?>" <?php if($key==2){echo "checked";}?>> <div class="radio-label bold"><?php echo $exp;?><span class="bold"> $<?php echo $expressPrice[$key];?></span></div><br>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </li>
                        </ul><!-- /.tabled-data -->

                        <ul id="total-field" class="tabled-data inverse-bold ">
                            <li>
                                <label>订单总额</label>
                                <div class="value" style="width:100%;text-align:right">$<?php echo $total+$expressPrice['2'];?></div>
                            </li>
                        </ul><!-- /.tabled-data -->

                    </div><!-- /#subtotal-holder -->
                </div><!-- /.col -->
            </div><!-- /#total-area -->

            <div id="payment-method-options">
                    <div class="payment-method-option">
                        <input class="le-radio" type="radio" name="paymethod" value="Direct">
                        <div class="radio-label bold ">微信支付</div>
                    </div><!-- /.payment-method-option -->
                    
                    <div class="payment-method-option">
                        <input class="le-radio" type="radio" name="paymethod" value="alipay">
                        <div class="radio-label bold ">支付宝支付</div>
                    </div><!-- /.payment-method-option -->
                    
                    <div class="payment-method-option">
                        <input class="le-radio" type="radio" name="paymethod" value="paypal">
                        <div class="radio-label bold ">网银支付</div>
                    </div><!-- /.payment-method-option -->
            </div><!-- /#payment-method-options -->
            
            <div class="place-order-button">
                <button class="le-button big">确认订单</button>
            </div><!-- /.place-order-button -->

        </div><!-- /.col -->
        <?php ActiveForm::end();?>
    </div><!-- /.container -->    
</section><!-- /#checkout-page -->
<!-- ========================================= CONTENT : END ========================================= -->		<!-- ============================================================= FOOTER ============================================================= -->