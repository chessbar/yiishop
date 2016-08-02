<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/2
 * Time: 上午11:49
 */

namespace app\models;
use Yii;
use yii\db\ActiveRecord;

class Cart extends  ActiveRecord
{
    public static function tableName()
    {
        return "{{%cart}}";
    }
    public function rules()
    {
        return [
            [['productid','productnum','uid','price',],'required'],
            ['createtime','safe']
        ];
    }
}