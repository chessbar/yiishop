<?php
namespace app\modules\controllers;
use app\modules\controllers\CommonController;
use Yii;
use app\models\Category;
use app\models\Product;
use crazyfd\qiniu\Qiniu;
use yii\data\Pagination;
class ProductController extends CommonController
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
			$qiniu = new Qiniu(Product::AK,Product::SK,Product::DOMAIN,Product::BUCKET);
			$post['Product']['cover'] = $model->cover;
			if($_FILES['Product']['error']['cover'] ==0){
				$key = uniqid();
				$qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
				$post['Product']['cover'] = $qiniu->getLink($key);
				$qiniu->delete(basename($model->cover));
			}
			$pics =[];
			foreach ($_FILES['Product']['tmp_name']['pics'] as $k=>$file) {
				if($_FILES['Product']['error']['pics'][$k] > 0){
					continue;
				}
				$key = uniqid();
				$qiniu->uploadFile($file,$key);
				$pics[$key]=$qiniu->getLink($key);
			}
			$post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics,true),$pics));
			if($model->load($post) && $model->save()){
				Yii::$app->session->setFlash('info','修改成功!');
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
	public function actionRemovepic()
	{
		$key = Yii::$app->request->get("key");
		$productid = Yii::$app->request->get("productid");
		$model = Product::find()->where('id = :pid', [':pid' => $productid])->one();
		$qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
		$qiniu->delete($key);
		$pics = json_decode($model->pics, true);
		unset($pics[$key]);
		Product::updateAll(['pics' => json_encode($pics)], 'id = :pid', [':pid' => $productid]);
		return $this->redirect(['product/mod', 'productid' => $productid]);
	}
	public function actionDel()
	{
		$productid = Yii::$app->request->get('productid');
		$model = Product::find()->where("id=:id",[':id'=>$productid])->one();
		$key = basename($model->cover);
		$qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
		$qiniu->delete($key);
		$pics = json_decode($model->pics,true);
		foreach ($pics as $key=>$file) {
			$qiniu->delete($key);
		}
		Product::deleteAll('id=:id',[':id'=>$productid]);
		return $this->redirect(['product/products']);
	}

	public function actionOn(){
		$productid = Yii::$app->request->get('productid');
		Product::updateAll(['isshelve'=>'1'],'id=:id',[':id'=>$productid]);
		return $this->redirect(['product/products']);
	}
	public function actionOff(){
		$productid = Yii::$app->request->get('productid');
		Product::updateAll(['isshelve'=>'0'],'id=:id',[':id'=>$productid]);
		return $this->redirect(['product/products']);
	}
}