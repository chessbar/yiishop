<?php
namespace app\controllers;
use app\controllers\CommonController;
use app\models\Product;
use app\models\Cart;
use Yii;
class CartController extends CommonController
{
public $layout = "layout1";
public function actionIndex()
{
	if(Yii::$app->session['isLogin'] !=1){
		return $this->redirect(['member/auth']);
	}
	$uid = Yii::$app->session['uid'];
	//视图
	$cart= Cart::find()->joinWith('product')->where('uid=:uid',[':uid'=>$uid])->all();
	return $this->render("index",['cart'=>$cart]);
}
public function actionAdd()
{
	if(Yii::$app->session['isLogin'] !=1){
		return $this->redirect(['member/auth']);
	}
	$uid = Yii::$app->session['uid'];
	if(Yii::$app->request->isGet)
	{
		$productid = Yii::$app->request->get('productid');
		$model = Product::find()->where('id=:pid',[':pid'=>$productid])->one();
		$price = $model->issale ? $model->saleprice : $model->price;
		$num = Yii::$app->request->get('productnum') ? Yii::$app->request->get('productnum') : 1;
		$data['Cart'] = ['productid'=>$productid,'productnum'=>$num,'price'=>$price,'uid'=>$uid];
	}
	//
	if(!$model = Cart::find()->where('productid=:pid and uid=:uid',[':pid'=>$productid,':uid'=>$uid])->one())
	{
		$model = new Cart;
	}else{
		$data['Cart']['productnum']=$model->productnum + $num;
	}
	$data['Cart']['createtime'] = time();
	$model->load($data);
	$model->save();
	return $this->redirect(['cart/index']);
}
	public function actionMod()
	{
		$cartid = Yii::$app->request->get('cartid');
		$productnum = Yii::$app->request->get('productnum');
		Cart::updateAll(['productnum'=>$productnum],'cartid=:cartid',[':cartid'=>$cartid]);
	}
	public function actionDel()
	{
		$cartid = Yii::$app->request->get('cartid');
		Cart::deleteAll('cartid=:cartid',[':cartid'=>$cartid]);
		return $this->redirect(['cart/index']);
	}
}