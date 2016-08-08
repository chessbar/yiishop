<!-- ============================================================= HEADER : END ============================================================= -->		<div id="single-product">
<div class="container" style="padding-top:10px">
  <?php foreach($orders as $order):?>
  <div style="margin-bottom:30px;">
  <div class="trade-order-mainClose">
    <div>
      <table style="width:100%;border-collapse:collapse;border-spacing:0px;background-color:#f5f5f5;">
        <colgroup>
          <col style="width:38%;">
          <col style="width:10%;">
          <col style="width:5%;">
          <col style="width:12%;">
          <col style="width:12%;">
          <col style="width:11%;">
          <col style="width:12%;">
        </colgroup>
        <tbody>
          <tr style="width:100%">
            <td style="padding:10px 20px;text-align:left;">
              <label>
                <input type="checkbox" disabled="" style="margin-right:8px;">
                <strong title="<?php echo date('Y-m-d H:i:s',$order->createtime);?>" style="margin-right:8px;font-weight:bold;">
                  <?php echo date('Y-m-d',$order->createtime);?>
                </strong>
              </label>
              <span>
                订单号：
              </span>
              <span>
              </span>
              <span>
                <?php echo $order->orderid;?>
              </span>
            </td>

          </tr>
        </tbody>
      </table>
      <table style="width:100%;border-collapse:collapse;border-spacing:0px;">
        <colgroup>
          <col style="width:38%;">
          <col style="width:10%;">
          <col style="width:5%;">
          <col style="width:12%;">
          <col style="width:12%;">
          <col style="width:11%;">
          <col style="width:12%;">
        </colgroup>
        <tbody>
          <?php foreach($order->products as $key=>$pro):?>
          <tr>
            <td style="text-align:left;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:0;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;padding-left:20px;" >
              <div style="overflow:hidden;">
                <a class="tp-tag-a" href="" style="float:left;width:27%;margin-right:2%;text-align:center;" target="_blank">
                  <img src="<?php echo $pro->cover;?>-coversmall" style="border:1px solid #E8E8E8;max-width:80px;">
                </a>
                <div style="float:left;width:71%;word-wrap:break-word;">
                  <div style="margin:0px;">
                    <a class="tp-tag-a" href="<?php echo yii\helpers\Url::to(['product/detail','productid'=>$pro->id]);?>" target="_blank">
                      <span>
                        <?php echo $pro->title;?>
                      </span>
                    </a>
                    <span>
                    </span>
                  </div>
                  <div style="margin-top:8px;margin-bottom:0;color:#9C9C9C;">
                    <span style="margin-right:6px;">
                      <span>
                        颜色分类
                      </span>
                      <span>
                        ：
                      </span>
                      <span>
                        红银战争机器-英国
                      </span>
                    </span>
                  </div>
                  
                  <span>
                  </span>
                </div>
              </div>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:0;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;">
              <div style="font-family:verdana;font-style:normal;">
                <?php if($pro->issale):?>
                <p>
                  <del style="color:#9C9C9C;">
                    <?php echo $pro->price;?>
                  </del>
                </p>
                <p>
                  <?php echo $pro->saleprice;?>
                </p>
                  <?php else:?>
                  <p>
                    <?php echo $pro->price;?>
                  </p>
                <?php endif;?>
                <span>
                </span>
                <span>
                </span>
              </div>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:0;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;">
              <div>
                <div>
                  <?php echo $pro->num;?>
                </div>
              </div>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:1px;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;" >
              <div>
                <div style="margin-bottom:3px;">
                  <span>
                    <span class="trade-ajax">
                      <span class="trade-tooltip-wrap">
                        <span>
                          <span class="trade-operate-text">

                          </span>
                        </span>
                      </span>
                      <noscript>
                      </noscript>
                    </span>
                  </span>
                </div>
                
              </div>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:1px;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;" >
              <?php if($key<=0):?>
              <div>
                <div style="font-family:verdana;font-style:normal;">
                  <span>
                  </span>
                  <span>
                  </span>
                  <p>
                    <strong>
                      <?php echo $order->amount;?>
                    </strong>
                  </p>
                  <span>
                  </span>
                </div>
                <p>
                  <span>
                    (含运费：
                  </span>
                  <span>
                    <?php echo Yii::$app->params['expressPrice'][$order->expressid];?>
                  </span>
                  <span>
                  </span>
                  <span>
                  </span>
                  <span>
                    )
                  </span>
                </p>
                
                <div>
                </div>
              </div>
              <?php endif;?>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:1px;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;" >
              <?php if($key<=0):?>
              <div>
                <div style="margin-bottom:3px;">
                  <a class="tp-tag-a" href="<?php echo yii\helpers\Url::to(['order/check','orderid'=>$order->orderid]);?>" target="_blank">
                    <?php echo $order->zhstatus;?>
                  </a>
                </div>
                <div>
                  <?php if(in_array($order->status,[220,255])): ?>
                  <div style="margin-bottom:3px;">
                    <span>
                      <a data="<?php echo $order->expressno;?>" class="tp-tag-a express" href="" target="_blank" style="position: relative;">
                        <span class="trade-operate-text">
                          查看物流
                        </span>
                        <div class="expressshow" style="display: none;overflow: auto;text-align: left;width: 210px;background: #f5f5f5;border:1px solid #ccc;position: absolute;top:18px;left: -35px;">

                        </div>
                      </a>
                    </span>
                  </div>
                  <?php endif;?>
                </div>
              </div>
              <?php endif;?>
            </td>
            <td style="text-align:center;vertical-align:top;padding-top:10px;padding-bottom:10px;border-right-width:0;border-right-style:solid;border-right-color:#E8E8E8;border-top-width:0;border-top-style:solid;border-top-color:#E8E8E8;" >
              <?php if($key<=0):?>
              <div>
                <div style="margin-bottom:3px;">
                  <span>
                    <a class="tp-tag-a" href="" target="_blank">
                      <span class="trade-operate-text">
                        <?php if(in_array($order->status,[220])):?>
                          <a href="<?php echo yii\helpers\Url::to(['order/received','orderid'=>$order->orderid]);?>">确认收货</a>
                          <?php elseif(in_array($order->status,[255])):?>
                            评论
                        <?php endif;?>
                      </span>
                    </a>
                  </span>
                </div>
              </div>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div>
      </div>
    </div>
  </div>
</div>
  <?php endforeach;?>

  <div class="pagination pull-right">
    <?php echo yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'activePageCssClass'=>"current",
        'disabledPageCssClass'=>'hidden',
        'maxButtonCount'=>5
    ]);?>
  </div>
</div>