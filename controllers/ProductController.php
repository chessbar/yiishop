<?php 
namespace app\controllers;
use app\controllers\CommonController;
use Yii;
use app\models\Product;
use app\models\Category;
use yii\data\Pagination;
class ProductController extends CommonController
{
	public $layout = "layout2";
	public function actionIndex()
	{
		$cateid=(int)Yii::$app->request->get('cateid');
		/*if(empty($cateid)){
			throw new  \Exception();
		}*/
		//根据cateid 先查询出其所得子类 根据cateid的数组查询出所有的商品
		$cateids = Category::getSonIds($cateid);
		$cateids[]=$cateid;
		$model = Product::find()->where(['in','cateid',$cateids])->andWhere('isshelve=:isshelve',[':isshelve'=>'1']);
		$count = $model->count();
		$pageSize=Yii::$app->params['pageSize']['showRroducts'];
		$pager = new Pagination(['pageSize'=>$pageSize,'totalCount'=>$count]);
		$products = $model->orderby('updatetime desc')->offset($pager->offset)->limit($pager->limit)->all();
		//推荐商品
		$recProducts=$model->andWhere('isrecommend=:isrecommend',[':isrecommend'=>'1'])->orderby('updatetime desc')->limit(13)->all();
		//特价商品
		$saleProducts=$model->andWhere('issale=:issale',[':issale'=>'1'])->orderby('updatetime desc')->limit(5)->all();
		//新品上市
		
		return $this->render("index",['products'=>$products,'recProducts'=>$recProducts,'saleProducts'=>$saleProducts,'pager'=>$pager]);
	}
	public function actionDetail()
	{
		$productid=(int)Yii::$app->request->get('productid');
		if(empty($productid)){
			throw new  \Exception();
		}
		//获取商品信息
		$detail = Product::find()->where('id=:id and isshelve=:isshelve',[':id'=>$productid,':isshelve'=>'1'])->one();
		return $this->render("detail",['detail'=>$detail]);
	}
}


