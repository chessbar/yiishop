<?php
/**
 * Created by PhpStorm.
 * User: mm
 * Date: 16/8/5
 * Time: 上午11:05
 */

namespace app\models;


use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public static function tableName()
    {
       return "{{%address}}";
    }
    public function rules()
    {
        return [
            [['uid','firstname','lastname','address','email','telphone'],'required'],
            [['createtime','postcode','company'],'safe']
        ];
    }
}