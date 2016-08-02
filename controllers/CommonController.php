<?php 
namespace app\controllers;
use yii\web\Controller;
use app\models\Test;
use app\models\Category;
class CommonController extends Controller
{	
	public function init()
	{
		//获取菜单数据
		$menu = Category::getMenu();
		$this->view->params['menu']=$menu;
	}
}