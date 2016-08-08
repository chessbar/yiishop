<?php 
namespace app\modules\controllers;
use app\modules\controllers\CommonController;
use Yii;
use app\models\Category;
class CategoryController extends CommonController
{
	public $layout ="layout1";
	public function actionCates()
	{
		$model = new Category;
		$cates = $model->getOptions(false);
		return $this->render('cates',['cates'=>$cates]);
	}
	public function actionAdd()
	{ 
		$model = new Category;
		$list = $model->getOptions();
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->add($post)){
				Yii::$app->session->setFlash('info','添加成功!');
			}
		}
		$model->title="";
		return $this->render('add',['list'=>$list,'model'=>$model]);
	}
	public function actionMod()
	{
		$cateid=Yii::$app->request->get('cateid');
		if(empty($cateid)){
			throw new \Exception();
		}
		$model = Category::find()->where('cateid=:id',[':id'=>$cateid])->one();
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->mod($post)){
				Yii::$app->session->setFlash('info','编辑成功!');
			}
		}
		$list = $model->getOptions();
		return $this->render("mod",['model'=>$model,'list'=>$list]);
	}
	public function actionDel()
	{
		try{
			$cateid = Yii::$app->request->get('cateid');
			if(empty($cateid)){
				throw new \Exception("参数错误！");
			}
			// 查看类别下是否有子类
			$data = Category::find()->where('pid=:pid',[':pid'=>$cateid])->one();
			if($data)
			{
				throw new \Exception("删除失败，该类下有子类！");
			}
			if(!Category::deleteAll('cateid=:id',[':id'=>$cateid])){
				throw new \Exception("删除出错，请重试！");
			}
		}catch(\Exception $e){
			Yii::$app->session->setFlash('info',$e->getMessage());
		}
		return $this->redirect(['category/cates']);
	}
}












