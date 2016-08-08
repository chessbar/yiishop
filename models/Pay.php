<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/5
 * Time: 下午9:05
 */

namespace app\models;

use app\models\Order;
use app\models\OrderDetail;
class Pay
{
    public static function alipay($orderid)
    {
        $amount = Order::find()->where('orderid =:oid',[':oid'=>$orderid])->one()->amount;
        if(!empty($amount)){
           $alipay = new \AlipayPay();
            $giftname ="商品名称";
            $data = OrderDetail::find()->where('orderid=:oid',[':oid'=>$orderid])->all();
            $body ="";
            foreach($data as $pro){
                $body .= $pro->productnum;
            }
            $showUrl ="http://chessbar.cc";//网站地址
            $html = $alipay->requestPay($orderid,$giftname,$amount,$body,$showUrl);
            echo $html;
        }
    }
    public static function notify($data)
    {
        $alipay = new \AlipayPay();
        $verify_result = $alipay->verifyNotify();
        if($verify_result){
            $out_trade_no = $data['extra_common_param'];
            $trade_no = $data['trade_no'];
            $trade_satuts = $data['trade_status'];
            $status = Order::PAYFAILED;
            if($trade_satuts == 'TRADE_FINISHED' || $trade_satuts == "TRADE_SUCCESS"){
                $status = Order::PAYSUCCESS;
                $order_info = Order::find()->where('orderid =:oid',[':oid'=>$out_trade_no])->one();
                if(!$order_info){
                    return false;
                }
                if($order_info->sataus == Order::CHECKORDER){
                    Order::updateAll(['status'=>$status,'tradeno'=>$trade_no,'tradeext'=>json_encode($data)],'orderid =:oid',[':oid'=>$out_trade_no]);
                }else{
                    return false;
                }
                return true;
            }
        }
        else{
            return false;
        }
    }
}