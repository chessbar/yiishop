<?php 
namespace app\controllers;
use Yii;
use app\models\Order;
use app\models\Cart;
use app\models\Product;
use app\models\OrderDetail;
use app\models\Address;
use app\models\Pay;
use app\controllers\CommonController;
use yii\data\Pagination;
use dzer\express\Express;
class OrderController extends CommonController
{
	public function init()
	{
		parent::init();
		if(Yii::$app->session['isLogin'] != 1)
		{
			return $this->redirect(['member/auth']);
		}
	}
	public function actionIndex()
	{
		$this->layout = "layout2";
		$uid = Yii::$app->session['uid'];
		$model = Order::find()->where('status > 0 and uid =:uid',[':uid'=>$uid]);
		$count =$model->count();
		$pager = new Pagination(['totalCount'=>$count,'pageSize'=>Yii::$app->params['pageSize']['frontOrders']]);
		$data = $model->orderBy('createtime desc')->offset($pager->offset)->limit($pager->limit)->all();
		$orders = Order::getProducts($data);
		return $this->render("index",['orders'=>$orders,'pager'=>$pager]);
	}
	public function actionCheck()
	{
		if(Yii::$app->session['isLogin'] != 1)
		{
			return $this->redirect(['member/auth']);
		}
		$orderid = Yii::$app->request->get('orderid');
		$status = Order::find()->where('orderid=:oid',[':oid'=>$orderid])->one()->status;
		if($status != Order::CREATEORDER && $status != Order::CHECKORDER ){
			return $this->redirect(['order/index']);
		}
		$uid = Yii::$app->session['uid'];
		//查询地址
		$addresses = Address::find()->where('uid=:uid',[':uid'=>$uid])->asArray()->all();
		//查询订单详情
		$details = OrderDetail::find()->where('orderid =:oid',[':oid'=>$orderid])->asArray()->all();
		$express = Yii::$app->params['express'];
		$expressPrice = Yii::$app->params['expressPrice'];
		$this->layout = "layout1";
		return $this->render("check",['addresses'=>$addresses,'details'=>$details,'express'=>$express,'expressPrice'=>$expressPrice]);
	}
	public function actionAdd()
	{
		if(Yii::$app->session['isLogin'] != 1)
		{
			return $this->redirect(['member/auth']);
		}
		//使用事务处理
		$transaction = Yii::$app->db->beginTransaction();
		try{
			if(Yii::$app->request->isPost)
			{
				$post = Yii::$app->request->post();
				$ordermodel = new Order();
				$ordermodel->scenario = 'add';
				$uid = Yii::$app->session['uid'];
				$ordermodel->uid = $uid;
				$ordermodel->status = Order::CREATEORDER;
				$ordermodel->createtime = time();
				if(!$ordermodel->save()){
					throw new \Exception('订单添加失败');
				}
				$orderid = $ordermodel->getPrimaryKey();
				foreach($post['OrderDetail'] as $product)
				{
					$model = new OrderDetail;
					$product['orderid'] = $orderid;
					$product['createtime'] = time();
					$data['OrderDetail']=$product;
					if(!$model->add($data)){
						throw new \Exception('订单详情添加失败');
					}
					//清空购物车
					Cart::deleteAll('productid=:pid',[':pid'=>$product['productid']]);
					//改变商品的库存
					Product::updateAllCounters(['num'=>-$product['productnum']],'id=:pid',[':pid'=>$product['productid']]);
				}
			}
			$transaction->commit();
		}catch(\Exception $e)
		{
			//回滚
			$transaction->rollBack();
			//跳回
			return $this->redirect(['cart/index']);
		}
		return $this->redirect(['order/check','orderid'=>$orderid]);
	}
	public function actionConfirm()
	{
		try{
			if(!Yii::$app->request->isPost){
				throw new \Exception();
			}
			$post = Yii::$app->request->post();
			$uid = Yii::$app->session['uid'];
			$orderid = $post['orderid'];
			$model = Order::find()->where('orderid =:oid and uid =:uid',[':oid'=>$orderid,':uid'=>$uid])->one();
			if(empty($model)){
				throw new \Exception();
			}
			$model->scenario = "update";
			$post['status'] = Order::CHECKORDER;
			$amount = 0;
			$details = OrderDetail::find()->where('orderid=:oid',[':oid'=>$orderid])->all();
			foreach($details as $detail)
			{
				$amount += $detail->productnum*$detail->price;
			}
			if($amount <=0 ){
				throw new \Exception();
			}
			$express = Yii::$app->params['expressPrice'][$post['expressid']];

			if($express <=0 ){
				throw new \Exception();
			}
			$amount += $express;
			$post['amount'] = $amount;
			$data['Order'] = $post;
			if($model->load($data) && $model->save()){
				return $this->redirect(['order/pay','orderid'=>$orderid,'paymethod'=>$post['paymethod']]);
			}
		}catch (\Exception $e){
			return $this->redirect(['index/index']);
		}
	}
	public function actionPay()
	{
		try{
			$orderid = Yii::$app->request->get('orderid');
			$paymethod = Yii::$app->request->get('paymethod');
			if(empty($orderid) or empty($paymethod)){
				throw new \Exception();
			}
			//p
			if($paymethod == 'alpay')
			{
				return Pay::alipay($orderid);
			}
		}catch (\Exception $e){
			return $this->redirect(['order/index']);
		}
	}
	public function actionGetexpress()
	{
		$expressno = Yii::$app->request->get('expressno');
		$res = Express::search($expressno);
		echo $res;
		exit;
	}
	public function actionReceived()
	{
		$orderid = Yii::$app->request->get('orderid');
		$order = Order::find()->where('orderid=:oid',[':oid'=>$orderid])->one();
		if(!empty($order) && $order->status==Order::SENDED){
			$order->status = Order::RECEIVED;
			$order->save();
		}
		return $this->redirect(['order/index']);
	}
}

