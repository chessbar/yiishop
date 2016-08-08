<?php 
namespace app\controllers;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
class CommonController extends Controller
{	
	public function init()
	{
		//获取菜单数据
		$menu = Category::getMenu();
		//推荐商品
		$recProducts = Product::find()->where('isshelve=:isshelve and isrecommend =:isrec',[':isshelve'=>'1',':isrec'=>'1'])->orderBy('updatetime desc,id desc')->limit(4)->all();
		//新品上架
		$newProducts = Product::find()->where('isshelve=:isshelve',[':isshelve'=>'1'])->orderBy('createtime desc,id desc')->limit(4)->all();
		//最佳热卖
		$saleProducts = Product::find()->where('isshelve=:isshelve and issale =:issale',[':isshelve'=>'1',':issale'=>'1'])->orderBy('updatetime desc,id desc')->limit(4)->all();
		$this->view->params['recProducts']=$recProducts;
		$this->view->params['newProducts']=$newProducts;
		$this->view->params['saleProducts']=$saleProducts;
		$this->view->params['menu']=$menu;
	}
}