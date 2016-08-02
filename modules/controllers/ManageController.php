<?php 
namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use app\modules\models\Admin;
use yii\data\Pagination;

class ManageController extends Controller
{
	public function actionManagers()
	{
		$this->layout = "layout1";
		$model =Admin::find();
		$count = $model->count();
		$pageSize=Yii::$app->params['pageSize']['managers'];
		$pager=new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
		$managers=$model->offset($pager->offset)->limit($pager->limit)->all();
		return $this->render("managers",['managers'=>$managers,'pager'=>$pager]);
	}
	public function actionReg()
	{
		$this->layout = "layout1";
		$model = new Admin;
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->reg($post)){
				Yii::$app->session->setFlash("info",'创建成功!');
			}else{
				Yii::$app->session->setFlash("info",'创建失败!');
			}
			$model->pwd='';
			$model->repwd='';
		}
		return $this->render("reg",['model'=>$model]);
	}
	public function actionDel()
	{
		$id = (int)Yii::$app->request->get('id');
		if(empty($id)){
			$this->redirect(['manage/managers']);
		}
		$model = new Admin;
		if($model->deleteAll('id=:id',[':id'=>$id])){
			Yii::$app->session->setFlash('info',"删除成功!");
			$this->redirect(['manage/managers']);
		}
	}
	public function actionMailchangepass()
	{
		$this->layout = false;
		$time = Yii::$app->request->get('timestamp');
		$user = Yii::$app->request->get('user');
		$token = Yii::$app->request->get('token');
		$model = new Admin;
		$myToken = $model->createToken($user,$time);
		if($token !=$myToken){
			$this->redirect(['public/login']);
			Yii::$app->end();
		}
		if(time()-$time>300)
		{
			$this->redirect(['public/login']);
			Yii::$app->end();
		}
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->changePass($post))
			{
				Yii::$app->session->setFlash('info','密码修改成功');
			}
		}
		$model->user=$user;
		return $this->render("mailchangepass",["model"=>$model]);
	}
	public function actionChangeemail()
	{
		$this->layout = "layout1";
		$model = Admin::find()->where('user = :user',[':user'=> Yii::$app->session['admin']['user']])->one();
		if(Yii::$app->request->isPost)
		{
			$post=Yii::$app->request->post();
			if($model->changeemail($post))
			{
				Yii::$app->session->setFlash('info','修改成功!');
			}else{
				Yii::$app->session->setFlash('info','修改失败!');
			}
		}
		$model->pwd="";
		return $this->render("changeemail",['model'=>$model]);
	}
	public function actionChangepass()
	{
		$this->layout = "layout1";
		$model = Admin::find()->where('user = :user',[':user'=> Yii::$app->session['admin']['user']])->one();
		if(Yii::$app->request->isPost)
		{
			$post=Yii::$app->request->post();
			if($model->changepwd($post))
			{
				Yii::$app->session->setFlash('info','修改成功!');
			}else{
				Yii::$app->session->setFlash('info','修改失败!');
			}
		}
		$model->pwd="";
		$model->oldpwd="";
		$model->repwd="";
		return $this->render("changepass",['model'=>$model]);
	}
}