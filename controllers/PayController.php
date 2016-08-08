<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/6
 * Time: 下午2:45
 */

namespace app\controllers;

use app\controllers\CommonController;
use app\models\Pay;
use Yii;
class PayController extends CommonController
{
    //关闭掉csrf验证
    public $enableCsrfValidation = false;
    //通知接口
    public function actionNotify()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
           if(Pay::notify($post)){
               echo "success";
           }else{
               echo "fail";
               exit;
           }
        }
    }
    public function actionReturn()
    {
        $this->layout = "layou1";
        $status = Yii::$app->request->get('trade_status');
        if($status == 'TRADE_SUCCESS'){
            $s = 'ok';
        }else{
            $s = 'no';
        }
        return $this->render("status",['status'=>$s]);
    }
}