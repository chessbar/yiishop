<?php 
namespace app\controllers;
use Yii;
use app\models\Member;
use app\controllers\CommonController;
class MemberController extends CommonController
{
	public $layout = "layout2";
	public function actionAuth()
	{
		if(Yii::$app->session['isLogin']){
			$this->redirect(['index/index']);
			Yii::$app->end();
		}
		$model = new Member;
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->login($post)){
				$this->goback();
				Yii::$app->end();
			}
		}
		$model->userpass="";
		return $this->render("auth",['model'=>$model]);
	}
	public function actionLogout()
	{
		Yii::$app->session->remove('logintime');	
		Yii::$app->session->remove('isLogin');	
		if(!isset(Yii::$app->session['isLogin'])){
			$this->goBack(Yii::$app->request->referrer);
		}
	}
	//邮箱注册
	public function actionMailreg()
	{
		if(Yii::$app->session['isLogin']){
			$this->redirect(['index/index']);
			Yii::$app->end();
		}
		$model = new Member;
		if(Yii::$app->request->isPost){
			
			$post = Yii::$app->request->post();
			if($model->mailreg($post)){
				Yii::$app->session->setFlash('info','注册成功，请查询邮件！');
			}else{
				Yii::$app->session->setFlash('info','注册失败，请重试！');
			}
		}
		return $this->render("auth",['model'=>$model]);
	}
}