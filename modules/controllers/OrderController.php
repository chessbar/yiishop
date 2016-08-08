<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/6
 * Time: 下午4:08
 */

namespace app\modules\controllers;
use yii\data\Pagination;
use app\modules\controllers\CommonController;
use app\models\Order;
use Yii;
class OrderController extends CommonController
{
    public $layout="layout1";
    public function actionOrders()
    {
        $model = Order::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['orders'];
        $pager = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
        $data = $model->offset($pager->offset)->limit($pager->limit)->all();
        //获取订单的详细信息
        $data = Order::getDetail($data);
        return $this->render('orders',['orders'=>$data,'pager'=>$pager]);
    }
    public function actionDetail()
    {
        $orderid = (int)Yii::$app->request->get('orderid');
        $order = Order::find()->where('orderid=:oid',[':oid'=>$orderid])->one();
        $detail = Order::getData($order);
        return $this->render('detail',['detail'=>$detail]);
    }
    //发货
    public function actionSend()
    {
        $orderid = (int)Yii::$app->request->get('orderid');
        $model = Order::find()->where('orderid=:oid',[':oid'=>$orderid])->one();
        $model->scenario = "send";
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $model->status = Order::SENDED;
            if($model->load($post) && $model->save()){
                return $this->redirect(['order/orders']);
            }else{
                Yii::$app->session->setFlash('info','发货失败');
            }
        }
        return $this->render('send',['model'=>$model]);
    }
}