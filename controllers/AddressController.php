<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/5
 * Time: 下午1:16
 */

namespace app\controllers;

use app\controllers\CommonController;
use app\models\Address;
use Yii;
class AddressController extends CommonController
{
    public function init()
    {
        if(Yii::$app->session['isLogin'] !=1)
        {
            return $this->redirect(['member/auth']);
        }
    }
    public function actionAdd()
    {
        $uid = Yii::$app->session['uid'];
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $post['uid']=$uid;
            $post['address']=$post['address1'].$post['address2'];
            $data['Address']=$post;
            $model = new Address;
            $model->load($data);
            $model->save();
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function actionDel()
    {
        $addressid = Yii::$app->request->get('addressid');
        $uid = Yii::$app->session['uid'];
        if(!Address::find()->where('uid=:uid and addressid=:aid',[':uid'=>$uid,':aid'=>$addressid])->one())
        {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        Address::deleteAll('addressid=:aid',[':aid'=>$addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}