<div class="content">
    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>订单详情</h3></div>
            <div class="row-fluid">
                <p>订单编号：<?php echo $detail->orderid;?></p>
                <p>下单用户：<?php echo $detail->username;?></p>
                <p>收货地址：<?php echo $detail->address;?></p>
                <p>订单总价：<?php echo $detail->amount;?></p>
                <p>快递方式：<?php echo \Yii::$app->params['express'][$detail->expressid];?></p>
                <p>快递编号：<?php echo $detail->expressno;?></p>
                <p>订单状态：<?php echo $detail->zhstatus;?></p>
                <p>商品列表：</p>
                <p>
                <?php foreach($detail->products as $pro):?>
                <div>
                    <?php echo $pro->productnum;?> x <a href="<?php echo yii\helpers\Url::to(['/product/detail','productid'=>$pro->productid]);?>" target="_blank"><?php echo $pro->productname;?></a>
                </div>
                <?php endforeach;?>
                </p>
            </div>
        </div>
    </div>
</div>