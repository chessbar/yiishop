<?php 
namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use app\models\Member;
use app\models\Member_profile;
use yii\data\Pagination;
class UserController extends Controller
{
	public function actionUsers()
	{
		$this->layout = "layout1";
		$model=Member::find()->joinWith('member_profile');
		$count=$model->count();
		$pageSize=Yii::$app->params['pageSize']['users'];
		$pager = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
		$users = $model->offset($pager->offset)->limit($pager->limit)->all();
		return $this->render("users",['users'=>$users,'pager'=>$pager]);
	}
	public function actionReg()
	{
		$this->layout = "layout1";
		$model=new Member;
		if(Yii::$app->request->isPost)
		{
			$post = Yii::$app->request->post();
			if($model->reg($post)){
				Yii::$app->session->setFlash('info','创建成功!');
			}else{
				Yii::$app->session->setFlash('info','创建失败!');
			}
		}
		$model->userpass="";
		$model->reuserpass="";
		return $this->render("reg",['model'=>$model]);
	}
	public function actionDel()
	{
		try{
			$uid=(int)Yii::$app->request->get('uid');
			if(empty($uid)){
				throw new \Exception();
			}
			$trans=Yii::$app->db->beginTransaction();
			if($obj=Member_profile::find()->where('uid =:uid',[':uid'=>$uid])->one())
			{
				$res = Member_profile::deleteAll('uid =:uid',[':uid'=>$uid]);
				if(!$res){
					throw new \Exception();
				}
			}
			if(!Member::deleteAll('uid =:uid',[':uid'=>$uid])){
				throw new \Exception();
			}
			$trans->commit();
		}catch(\Exception $e){
			if(Yii::$app->db->getTransaction()){
				$trans->rollback();
			}
		}
		$this->redirect(['user/users']);
	}
}