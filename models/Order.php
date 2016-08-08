<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/5
 * Time: 上午9:42
 */

namespace app\models;

use yii\db\ActiveRecord;
use app\models\OrderDetail;
use app\models\Member;
use app\models\Address;
use app\models\Product;
use app\models\Category;
use Yii;
class Order extends ActiveRecord
{
    const CREATEORDER = 0;
    const CHECKORDER =100;
    const PAYFAILED = 201;
    const PAYSUCCESS = 202;
    const SENDED = 220;
    const RECEIVED = 255;
    public $products;
    public $zhstatus;
    public $username;
    public $address;
    public static $status = [
        self::CHECKORDER => '订单初始化',
        self::CHECKORDER => '待支付',
        self::PAYFAILED => '支付失败',
        self::PAYSUCCESS => '等待发货',
        self::SENDED => '已发货',
        self::RECEIVED => '订单完成'
    ];
    public static function tableName()
    {
        return "{{%order}}";
    }
    public function rules()
    {
        return [
            [['uid','status'],'required','on'=>['add']],
            [['addressid','expressid','amount','status'],'required','on'=>['update']],
            ['expressno','required','message'=>'请输入快递单号','on'=>['send']],
            ['createtime','safe','on'=>['add']],
        ];
    }
    public function attributeLabels()
    {
       return [
           'expressno'=>'快递单号',
       ];
    }
    public function getDetail($orders)
    {
        foreach($orders as $order){
            $order = self::getData($order);
        }
        return $orders;
    }
    public static function getData($order){
        $products = OrderDetail::find()->where('orderid=:oid',[':oid'=>$order->orderid])->all();
        $order->products=$products;
        $order->username = Member::find()->where('uid=:uid',[':uid'=>$order->uid])->one()->username;
        $order->address = Address::find()->where('addressid=:aid',[':aid'=>$order->addressid])->one();
        if(empty($order->address)){
            $order->address="";
        }else{
            $order->address=$order->address->address;
        }
        $order->zhstatus = self::$status[$order->status];
        return $order;
    }
    public static function getProducts($orders){

        foreach($orders as $order)
        {
            $details = OrderDetail::find()->where('orderid =:oid',[':oid'=>$order->orderid])->all();
            $products=[];
            foreach($details as $detail){
                $product = Product::find()->where('id =:pid',[':pid'=>$detail->productid])->one();
                $product->cate = Category::find()->where('cateid=:cid',[':cid'=>$product->cateid])->one()->title;
                $product->num = $detail->productnum;
                $products[]=$product;
            }
            $order->zhstatus = self::$status[$order->status];
            $order->products = $products;
        }
        return $orders;
    }

}