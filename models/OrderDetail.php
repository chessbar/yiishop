<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/5
 * Time: 上午9:56
 */

namespace app\models;

use yii\db\ActiveRecord;

class OrderDetail extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%order_detail}}";
    }
    public function rules()
    {
        return [
            [['productid','productname','productnum','price','orderid','createtime'],'required']
        ];
    }
    public function add($data)
    {
        if($this->load($data) && $this->save())
        {
            return true;
        }
        return false;
    }
}