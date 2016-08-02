<?php 
namespace app\modules\controllers;
use yii\web\Controller;
use Yii;
use app\models\Category;
use app\models\Product;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;
class ProductController extends Controller
{
	public $layout = "layout1";
	public function actionProducts()
	{
		$model = Product::find();
		$count = $model->count();
		$pageSize = Yii::$app->params['pageSize']['products'];
		$pager = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
		$products = $model->offset($pager->offset)->limit($pager->limit)->all();
		return $this->render('products',['products'=>$products,'pager'=>$pager]);
	}
	public function actionAdd()
	{
		$cate = new Category;
		$list = $cate->getOptions(false);
		$model = new Product;
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			$pics = $this->upload();
			if(!$pics){
				$model->addError('cover','封面图片不能为空');
			}else{
				$post['Product']['cover'] = $pics['cover'];
				$post['Product']['pics'] = $pics['pics'];
			}
			if($pics && $model->add($post)){
				Yii::$app->session->setFlash('info','添加成功!');
			}else{
				Yii::$app->session->setFlash('info','添加失败!');
			}

		}
		return $this->render('add',['model'=>$model,'list'=>$list]);
	}
	public function actionMod()
	{
		$productid = Yii::$app->request->get('productid');
		if(empty($productid)){
			throw new \Exception();
		}
		$cate = new Category;
		$list = $cate->getOptions(false);
		$model = Product::find()->where('id=:id',[':id'=>$productid])->one();
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			$pics = $this->upload();
			if(!$pics){
				$model->addError('cover','封面图片不能为空');
			}else{
				$post['Product']['cover'] = $pics['cover'];
				$post['Product']['pics'] = $pics['pics'];
			}
			if($pics && $model->add($post)){
				Yii::$app->session->setFlash('info','添加成功!');
			}else{
				Yii::$app->session->setFlash('info','添加失败!');
			}

		}
		return $this->render('mod',['model'=>$model,'list'=>$list]);
	}
	private function upload(){
		if($_FILES['Product']['error']['cover'] > 0)
		{
			return false;
		}
		$qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
		$key = uniqid();
		$qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
		$cover = $qiniu->getLink($key);
		$pics=[];
		foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
			if($_FILES['Product']['error']['pics'][$k] >0)
			{
				continue;
			}else{
				$key = uniqid();
				$qiniu->uploadFile($file,$key);
				$pics[$key] = $qiniu->getLink($key);
			}
		}
		return ['cover'=>$cover,'pics'=>json_encode($pics)];
	}
}