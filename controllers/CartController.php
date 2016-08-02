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
	return $this->render("index");
}
public function actionAdd()
{
	if(Yii::$app->session['isLogin'] !=1){
		return $this->redirect(['member/auth']);
	}
	$uid = Yii::$app->session['uid'];
	echo $uid;
	exit;
	if(Yii::$app->request->isPost)
	{
		$post = Yii::$app->request->post();
		var_dump($post);
		$num=Yii::$app->request->post()['productnum'];
		$data['Cart']=$post;
		$data['Cart']['uid']=$uid;
		$productid=$data['Cart']['productid'];
	}
	if(Yii::$app->request->isGet)
	{
		$productid = Yii::$app->request->get('productid');
		$model = Product::find()->where('id=:pid',[':pid'=>$productid])->one();
		$price = $model->issale ? $model->saleprice : $model->price;
		$num =1;
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
}